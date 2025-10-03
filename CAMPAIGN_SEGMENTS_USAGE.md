# Campaign Segments - Include/Exclude System

## 📋 Overview

As campanhas agora suportam **múltiplos segmentos** com lógica de **inclusão e exclusão**, permitindo maior flexibilidade no direcionamento de assinantes.

## 🗄️ Estrutura do Banco de Dados

### Tabela `campaign_segment`
```sql
- id (uuid)
- campaign_id (uuid)
- segment_id (uuid)
- type (enum: 'include', 'exclude')
- timestamps
```

## 🔧 Relationships no Model Campaign

```php
// Todos os segmentos (incluídos e excluídos)
$campaign->segments; 

// Apenas segmentos incluídos
$campaign->includedSegments;

// Apenas segmentos excluídos
$campaign->excludedSegments;
```

## 💡 Como Usar

### 1️⃣ Sincronizar Segmentos em uma Campanha

```php
use App\Services\Campaign\SyncCampaignSegmentsService;

$syncService = new SyncCampaignSegmentsService();

$campaign = Campaign::find($campaignId);

$syncService->execute($campaign, [
    'included_segments' => [
        'segment-uuid-1',
        'segment-uuid-2',
        'segment-uuid-3',
    ],
    'excluded_segments' => [
        'segment-uuid-4',
        'segment-uuid-5',
    ],
]);
```

### 2️⃣ Obter Assinantes Alvo da Campanha

```php
use App\Services\Campaign\SyncCampaignSegmentsService;

$syncService = new SyncCampaignSegmentsService();
$campaign = Campaign::find($campaignId);

// Retorna Collection de Subscribers
// (incluídos - excluídos) com status 'active'
$targetSubscribers = $syncService->getTargetSubscribers($campaign);

echo "Total de assinantes alvo: " . $targetSubscribers->count();
```

### 3️⃣ Verificar Segmentos de uma Campanha

```php
$campaign = Campaign::with(['includedSegments', 'excludedSegments'])->find($id);

// Listar segmentos incluídos
foreach ($campaign->includedSegments as $segment) {
    echo "Incluído: {$segment->name}\n";
}

// Listar segmentos excluídos
foreach ($campaign->excludedSegments as $segment) {
    echo "Excluído: {$segment->name}\n";
}
```

### 4️⃣ Adicionar/Remover Segmentos Manualmente

```php
// Adicionar segmento como incluído
$campaign->segments()->attach($segmentId, ['type' => 'include']);

// Adicionar segmento como excluído
$campaign->segments()->attach($segmentId, ['type' => 'exclude']);

// Remover segmento específico
$campaign->segments()->detach($segmentId);

// Remover todos os segmentos
$campaign->segments()->detach();
```

## 🎯 Exemplo de Cenário Real

### Cenário: Newsletter apenas para usuários VIP que não sejam inativos

```php
$campaign = Campaign::create([
    'team_id' => $team->id,
    'name' => 'Newsletter VIP',
    'subject' => 'Novidades Exclusivas',
    'body_html' => '<h1>Conteúdo VIP</h1>',
    'status' => 'draft',
]);

// Configurar segmentos
$syncService = new SyncCampaignSegmentsService();
$syncService->execute($campaign, [
    'included_segments' => [
        $vipSegmentId,
        $premiumSegmentId,
    ],
    'excluded_segments' => [
        $inactiveUsersSegmentId,
        $unsubscribedSegmentId,
    ],
]);

// Obter lista final de assinantes
$targetSubscribers = $syncService->getTargetSubscribers($campaign);

// Enviar campanha
foreach ($targetSubscribers as $subscriber) {
    // Lógica de envio
    echo "Enviando para: {$subscriber->email}\n";
}
```

## 🔄 Migration e Rollback

### Aplicar migration:
```bash
php artisan migrate
```

### Reverter migration:
```bash
php artisan migrate:rollback --step=1
```

## ✅ Vantagens do Sistema

1. ✨ **Flexibilidade**: Múltiplos segmentos incluídos e excluídos
2. 🎯 **Precisão**: Controle fino sobre quem recebe a campanha
3. 🚀 **Performance**: Queries otimizadas com relacionamentos Eloquent
4. 📊 **Rastreabilidade**: Histórico completo na tabela pivot
5. 🔒 **Integridade**: Foreign keys e unique constraints

## 🧪 Testando

```php
// Criar segmentos de teste
$segment1 = Segment::factory()->create(['team_id' => $team->id, 'name' => 'VIP']);
$segment2 = Segment::factory()->create(['team_id' => $team->id, 'name' => 'Premium']);
$segment3 = Segment::factory()->create(['team_id' => $team->id, 'name' => 'Inativos']);

// Criar campanha
$campaign = Campaign::factory()->create(['team_id' => $team->id]);

// Sincronizar
$syncService = new SyncCampaignSegmentsService();
$syncService->execute($campaign, [
    'included_segments' => [$segment1->id, $segment2->id],
    'excluded_segments' => [$segment3->id],
]);

// Verificar
assert($campaign->includedSegments->count() === 2);
assert($campaign->excludedSegments->count() === 1);
```

---

**Desenvolvido para TribeSend** 🚀

