# Comando: Relatório da Waitlist

## Descrição

O comando `waitlist:report` gera relatórios completos sobre as inscrições na waitlist do TribeSend, incluindo estatísticas de total, diário, semanal e mensal.

## Uso Básico

```bash
php artisan waitlist:report
```

## Opções

### `--json`
Exibe o relatório em formato JSON, útil para integração com outras ferramentas ou APIs.

```bash
php artisan waitlist:report --json
```

## Estatísticas Disponíveis

### 1. Total Geral
- **Total de Inscritos**: Número total de inscrições na waitlist
- **Confirmados**: Número de inscritos que confirmaram o email
- **Pendentes**: Número de inscritos aguardando confirmação

### 2. Estatísticas Diárias (Hoje)
- **Novos Inscritos**: Inscrições realizadas hoje
- **Confirmados**: Confirmações realizadas hoje
- **Pendentes**: Inscrições pendentes de hoje

### 3. Estatísticas Semanais (Últimos 7 dias)
- **Novos Inscritos**: Inscrições da semana atual
- **Confirmados**: Confirmações da semana atual
- **Pendentes**: Inscrições pendentes da semana atual
- **Crescimento**: Percentual de crescimento em relação à semana anterior

### 4. Estatísticas Mensais (Mês atual)
- **Novos Inscritos**: Inscrições do mês atual
- **Confirmados**: Confirmações do mês atual
- **Pendentes**: Inscrições pendentes do mês atual
- **Crescimento**: Percentual de crescimento em relação ao mês anterior

### 5. Métricas Adicionais
- **Taxa de Confirmação Geral**: Percentual de inscritos que confirmaram o email

## Exemplo de Saída (Texto)

```
📊 Gerando relatório da Waitlist...

═══════════════════════════════════════════════════
                    TOTAL GERAL                    
═══════════════════════════════════════════════════
+--------------------+------------+
| Métrica            | Quantidade |
+--------------------+------------+
| Total de Inscritos | 1250       |
| Confirmados        | 975        |
| Pendentes          | 275        |
+--------------------+------------+

═══════════════════════════════════════════════════
                 HOJE (07/10/2025)                 
═══════════════════════════════════════════════════
+-----------------+------------+
| Métrica         | Quantidade |
+-----------------+------------+
| Novos Inscritos | 45         |
| Confirmados     | 32         |
| Pendentes       | 13         |
+-----------------+------------+

═══════════════════════════════════════════════════
              ESTA SEMANA (7 dias)                 
═══════════════════════════════════════════════════
+-----------------+------------+
| Métrica         | Quantidade |
+-----------------+------------+
| Novos Inscritos | 320        |
| Confirmados     | 245        |
| Pendentes       | 75         |
+-----------------+------------+

   INFO  Crescimento: 15.5% em relação à semana anterior.

═══════════════════════════════════════════════════
        ESTE MÊS (October/2025)        
═══════════════════════════════════════════════════
+-----------------+------------+
| Métrica         | Quantidade |
+-----------------+------------+
| Novos Inscritos | 890        |
| Confirmados     | 680        |
| Pendentes       | 210        |
+-----------------+------------+

   INFO  Crescimento: 22.8% em relação ao mês anterior.

═══════════════════════════════════════════════════

   INFO  Taxa de Confirmação Geral: 78%.

═══════════════════════════════════════════════════

   SUCCESS  ✓ Relatório gerado com sucesso!
```

## Exemplo de Saída (JSON)

```json
{
    "total": {
        "count": 1250,
        "confirmed": 975,
        "pending": 275
    },
    "daily": {
        "count": 45,
        "confirmed": 32,
        "pending": 13
    },
    "weekly": {
        "count": 320,
        "confirmed": 245,
        "pending": 75
    },
    "monthly": {
        "count": 890,
        "confirmed": 680,
        "pending": 210
    },
    "growth": {
        "monthly": 22.8,
        "weekly": 15.5
    },
    "generated_at": "2025-10-07T23:21:25+00:00"
}
```

## Casos de Uso

### 1. Monitoramento Manual
Execute o comando diretamente para visualizar as estatísticas atuais da waitlist:
```bash
php artisan waitlist:report
```

### 2. Integração com Scripts
Use o formato JSON para integrar com scripts de monitoramento:
```bash
php artisan waitlist:report --json | jq '.total.count'
```

### 3. Agendamento (Cron)
Configure no cron para receber relatórios periódicos:
```bash
# Enviar relatório diário às 9h
0 9 * * * cd /path/to/tribesend && php artisan waitlist:report | mail -s "Relatório Waitlist" admin@tribesend.com
```

### 4. Dashboard Administrativo
Integre o relatório JSON em dashboards ou painéis administrativos:
```php
$report = Artisan::call('waitlist:report --json');
return response()->json(json_decode($report));
```

## Interpretação dos Dados

### Taxa de Crescimento
- **Positivo (+)**: Indica crescimento em relação ao período anterior (verde)
- **Negativo (-)**: Indica redução em relação ao período anterior (vermelho)
- **Zero (0)**: Sem dados do período anterior ou crescimento neutro

### Taxa de Confirmação
- **> 70%**: Excelente taxa de confirmação
- **50-70%**: Taxa aceitável
- **< 50%**: Requer atenção - possíveis problemas com emails de confirmação

## Testes

O comando possui cobertura completa de testes. Para executar:

```bash
php artisan test --filter=WaitlistReportCommandTest
```

## Arquivos Relacionados

- **Command**: `app/Console/Commands/WaitlistReportCommand.php`
- **Model**: `app/Models/Waitlist.php`
- **Factory**: `database/factories/WaitlistFactory.php`
- **Tests**: `tests/Feature/Commands/WaitlistReportCommandTest.php`
- **Migration**: `database/migrations/2025_10_03_233921_create_waitlists_table.php`

## Suporte

Para problemas ou sugestões relacionadas ao comando, abra uma issue no repositório do projeto.

