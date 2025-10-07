# TribeSend Favicon Guide

## Arquivos Criados

### Favicons SVG (Escaláveis)
- **favicon.svg** - Favicon principal SVG (32x32 viewBox)
- **favicon-16x16.svg** - Versão otimizada para 16x16 pixels
- **favicon-32x32.svg** - Versão otimizada para 32x32 pixels
- **apple-touch-icon.svg** - Ícone para dispositivos Apple (180x180 viewBox)

### Design
- Ícone: Avião de papel (send/enviar)
- Cores: Gradiente azul (#3B82F6 → #2563EB)
- Fundo: Círculo com gradiente
- Elemento: Avião de papel em branco

## Implementação

Os favicons já estão configurados em `resources/views/app.blade.php`:

```html
<!-- Favicons para diferentes tamanhos e dispositivos -->
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="icon" type="image/svg+xml" sizes="16x16" href="/favicon-16x16.svg">
<link rel="icon" type="image/svg+xml" sizes="32x32" href="/favicon-32x32.svg">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.svg">
<link rel="icon" href="/favicon.ico" sizes="any">

<!-- PWA Manifest -->
<link rel="manifest" href="/site.webmanifest">
```

## Compatibilidade

### ✅ Suporte Completo
- Chrome/Edge (moderno)
- Firefox
- Safari
- Opera
- Dispositivos iOS (via apple-touch-icon)

### 📱 PWA Support
O arquivo `site.webmanifest` está configurado para suporte a Progressive Web Apps.

## Gerando Versões PNG (Opcional)

Se você precisar de versões PNG para maior compatibilidade com navegadores antigos:

### Usando ImageMagick (no Docker)
```bash
# Instalar ImageMagick no container
docker compose exec app apk add imagemagick

# Converter para PNG
docker compose exec app convert public/favicon.svg -resize 16x16 public/favicon-16x16.png
docker compose exec app convert public/favicon.svg -resize 32x32 public/favicon-32x32.png
docker compose exec app convert public/apple-touch-icon.svg -resize 180x180 public/apple-touch-icon.png
```

### Usando ferramentas online
- https://realfavicongenerator.net/
- https://favicon.io/

## Customização

### Alterar Cores
Edite os arquivos SVG e modifique o gradiente:

```xml
<linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
  <stop offset="0%" style="stop-color:#3B82F6;stop-opacity:1" />
  <stop offset="100%" style="stop-color:#2563EB;stop-opacity:1" />
</linearGradient>
```

### Alterar Ícone
Modifique os elementos `<path>` nos arquivos SVG para mudar o desenho do avião de papel.

## Meta Tags Adicionadas

```html
<meta name="theme-color" content="#3B82F6">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="TribeSend">
```

Essas tags melhoram a experiência em dispositivos móveis e ao adicionar à tela inicial.

