# Tela de Onboarding - Criação de Teams

Este documento descreve a funcionalidade de onboarding para criação de teams no TribeSend.

## Funcionalidade

A tela de onboarding permite que novos usuários criem seu primeiro time de forma simples e intuitiva, coletando apenas as informações essenciais:

- **Nome do Time** (obrigatório)
- **Categoria** (obrigatório) 
- **Descrição** (opcional)

## Acesso

A tela de onboarding está disponível na rota:
- **URL**: `http://localhost/onboarding/team`
- **Rota nomeada**: `onboarding.team.create`

## Categorias Disponíveis

O sistema oferece as seguintes categorias para classificar o time:

- AI
- Developer
- Marketing  
- Game Development
- Journalist
- Writer
- Travel
- E-commerce
- Finance
- Healthcare
- Education
- Consulting
- Design
- Photography
- Music
- Sports
- Food & Beverage
- Real Estate
- Legal
- Non-profit
- Other

## Componentes Criados

### Backend

1. **Model**: `App\Models\Team`
   - Adicionado campo `category` no fillable
   - Relacionamentos com usuários e subscriptions

2. **Form Request**: `App\Http\Requests\Team\CreateTeamRequest`
   - Validação dos campos obrigatórios
   - Mensagens de erro personalizadas em português
   - Validação da categoria contra lista permitida

3. **Service**: `App\Services\Team\CreateTeamService`
   - Lógica de criação do team com transação
   - Associação automática do usuário como owner
   - Retorna o team criado com dados atualizados

4. **Controller**: `App\Http\Controllers\OnboardingController`
   - Método `create()` para exibir o formulário
   - Método `store()` para processar a criação
   - Redirecionamento para dashboard após sucesso

5. **Rotas**: `/routes/web.php`
   - GET `/onboarding/team` - Exibir formulário
   - POST `/onboarding/team` - Processar criação

### Frontend

1. **Página Vue**: `resources/js/pages/Onboarding/CreateTeam.vue`
   - Interface limpa usando AuthLayout
   - Formulário reativo com validação
   - Componentes shadcn-vue (Card, Input, Select, Textarea)
   - Estados de loading e validação
   - Ícones visuais para cada categoria

2. **Componentes shadcn-vue instalados**:
   - `select` - Para seleção de categoria
   - `textarea` - Para descrição opcional

## Banco de Dados

### Migrations Modificadas

1. **Teams Table**: Campo `category` já existia na migration
2. **Team-User Pivot Table**: 
   - Removido campo `id` desnecessário
   - Adicionado role `owner` além de `member` e `admin`
   - Chave primária composta por `team_id` e `user_id`

## Testes

### Testes de Onboarding (`OnboardingTest.php`)

- ✅ Exibição da página de onboarding
- ✅ Criação bem-sucedida de team
- ✅ Validação de campos obrigatórios
- ✅ Validação de categoria válida
- ✅ Descrição opcional funciona corretamente

### Testes de Fluxo Completo (`RegistrationOnboardingFlowTest.php`)

- ✅ Redirecionamento para onboarding após registro
- ✅ Fluxo completo: registro → onboarding → dashboard
- ✅ Verificação de criação de usuário e team
- ✅ Verificação de associação como owner

Para executar todos os testes relacionados:
```bash
php artisan test --filter="OnboardingTest|RegistrationOnboardingFlowTest"
```

## Fluxo de Uso

### Fluxo Completo (Registro -> Onboarding -> Dashboard)

1. **Registro de Usuário**:
   - Usuário acessa `/register`
   - Preenche dados de cadastro (nome, email, senha)
   - Após registro bem-sucedido, é **automaticamente redirecionado** para `/onboarding/team`

2. **Onboarding - Criação de Team**:
   - Usuário preenche nome do time (obrigatório)
   - Seleciona uma categoria da lista (obrigatório)  
   - Opcionalmente adiciona uma descrição
   - Clica em "Criar Time e Continuar"
   - Sistema cria o team e associa o usuário como owner

3. **Finalização**:
   - Redirecionamento para dashboard com mensagem de sucesso
   - Usuário já está logado e com seu primeiro team criado

### Acesso Direto ao Onboarding

Usuários já cadastrados também podem acessar diretamente:
- **URL**: `http://localhost/onboarding/team`
- **Rota nomeada**: `onboarding.team.create`

## Validações

- **Nome**: Obrigatório, string, máximo 255 caracteres
- **Categoria**: Obrigatório, deve estar na lista predefinida
- **Descrição**: Opcional, string, máximo 1000 caracteres

## Mensagens de Erro

Todas as mensagens estão em português:
- "O nome do time é obrigatório."
- "A categoria é obrigatória."
- "A categoria selecionada é inválida."

## Próximos Passos

Esta funcionalidade pode ser expandida com:
- Upload de logo do team
- Configuração de fuso horário
- Configuração de moeda
- Convite de membros iniciais
- Integração com planos de assinatura
