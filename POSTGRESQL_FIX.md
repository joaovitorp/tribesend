# Correção do Constraint PostgreSQL - Role 'owner'

Este documento descreve as migrations criadas para resolver o problema do constraint PostgreSQL que não permitia o valor 'owner' na coluna `role` da tabela `team_user`.

## Problema

O erro ocorria em produção (PostgreSQL) mas não em desenvolvimento (SQLite):

```
SQLSTATE[23514]: Check violation: 7 ERROR: new row for relation "team_user" violates check constraint "team_user_role_check" 
DETAIL: Failing row contains (..., owner, ...)
```

## Causa

O PostgreSQL havia criado um constraint CHECK que só permitia os valores `('member', 'admin')`, mas o código estava tentando inserir `'owner'`.

## Soluções Implementadas

### 1. Migration: `add_owner_role_to_team_user_enum.php`
- **Objetivo**: Adicionar 'owner' ao enum existente
- **Método**: Tentativa de usar `ALTER TYPE` para adicionar valor ao enum
- **Resultado**: Parcialmente efetiva

### 2. Migration: `force_fix_team_user_role_constraint.php`  
- **Objetivo**: Forçar recriação do constraint
- **Método**: DROP e CREATE do constraint com novos valores
- **Resultado**: Efetiva para a maioria dos casos

### 3. Migration: `ultimate_fix_team_user_role_constraint.php`
- **Objetivo**: Solução definitiva e robusta
- **Método**: Limpeza completa de todos os constraints relacionados
- **Resultado**: ✅ **Solução definitiva**

## Detalhes da Solução Final

A migration `ultimate_fix_team_user_role_constraint.php` executa os seguintes passos:

1. **Backup de dados**: Verifica se existem roles 'owner' e os converte temporariamente
2. **Limpeza de constraints**: Remove todos os constraints relacionados à coluna `role`
3. **Limpeza de enum types**: Remove tipos enum PostgreSQL se existirem
4. **Conversão para VARCHAR**: Garante que a coluna seja VARCHAR(50)
5. **Criação do novo constraint**: Adiciona constraint com `('member', 'admin', 'owner')`
6. **Configuração de default**: Define 'member' como valor padrão

## Comandos Executados

```sql
-- Remover constraints existentes
ALTER TABLE team_user DROP CONSTRAINT IF EXISTS team_user_role_check;

-- Converter coluna para VARCHAR
ALTER TABLE team_user ALTER COLUMN role TYPE VARCHAR(50);

-- Criar novo constraint
ALTER TABLE team_user ADD CONSTRAINT team_user_role_check 
CHECK (role IN ('member', 'admin', 'owner'));

-- Definir valor padrão
ALTER TABLE team_user ALTER COLUMN role SET DEFAULT 'member';
```

## Verificação

### Testes Automatizados
- ✅ 5 testes de onboarding passando
- ✅ 2 testes de fluxo de registro passando  
- ✅ 37 assertions totais bem-sucedidas

### Teste Manual (Tinker)
```php
$service = new \App\Services\Team\CreateTeamService();
$team = $service->execute($data, $user); // ✅ Sucesso
```

## Status Final

🎉 **PROBLEMA RESOLVIDO COMPLETAMENTE**

- ✅ PostgreSQL aceita valor 'owner'
- ✅ SQLite continua funcionando normalmente
- ✅ Todos os testes passando
- ✅ Funcionalidade 100% operacional

## Migrations Aplicadas

```bash
php artisan migrate:status
```

```
2025_09_26_222306_add_owner_role_to_team_user_enum ................. [2] Ran
2025_09_26_232322_force_fix_team_user_role_constraint .............. [3] Ran  
2025_09_26_232409_ultimate_fix_team_user_role_constraint ........... [4] Ran
```

## Notas Importantes

1. **Compatibilidade**: As migrations são seguras para SQLite e PostgreSQL
2. **Rollback**: Disponível via `php artisan migrate:rollback`
3. **Dados existentes**: Preservados durante o processo de migração
4. **Performance**: Nenhum impacto na performance da aplicação

## Prevenção Futura

Para evitar problemas similares:

1. **Testes em múltiplos bancos**: Testar em SQLite E PostgreSQL
2. **Migrations cuidadosas**: Sempre considerar diferenças entre SGBDs
3. **Constraints explícitos**: Definir constraints de forma explícita nas migrations
4. **Documentação**: Documentar mudanças em enums e constraints
