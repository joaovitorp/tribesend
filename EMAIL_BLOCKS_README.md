# 📧 Blocos de Email Marketing - TribeSend

## Visão Geral

Todos os blocos HTML foram **completamente reescritos** para serem compatíveis com clientes de email (Gmail, Outlook, Apple Mail, etc.). 

## ✅ Características dos Blocos

### 1. **Estrutura com Tabelas** (`<table>`)
- Todos os blocos usam `<table role="presentation">` ao invés de `<div>`
- Compatível com Outlook e outros clientes antigos
- `cellspacing="0" cellpadding="0" border="0"` em todas as tabelas

### 2. **CSS Inline**
- **TODOS os estilos são inline** (não há CSS externo)
- Font-family safe: `-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif`
- Cores em hexadecimal (não usa variáveis CSS)

### 3. **Responsividade**
- Largura máxima de 600px (padrão para emails)
- Imagens com `width="600" style="width: 100%; max-width: 600px;"`
- Layouts adaptáveis para mobile

### 4. **Acessibilidade**
- `role="presentation"` em tabelas de layout
- `alt` text em todas as imagens
- Cores com contraste adequado

## 📦 Categorias de Blocos

### 🎨 **Templates Completos** (2 blocos)
Emails completos prontos para uso:
- **Newsletter Completa**: Template profissional com header, conteúdo e footer
- **Email Promocional**: Template para promoções com destaque visual

### 📐 **Layout** (6 blocos)
Estruturas de seção:
- **Hero Centralizado**: Banner principal com CTA
- **Hero com Imagem de Fundo**: Banner com background image
- **Duas Colunas**: Layout lado a lado
- **Três Features**: Grid de 3 itens com ícones
- **Divisor**: Linha separadora
- **Espaçamento**: Espaço em branco
- **Footer Completo**: Rodapé com links e informações

### 📝 **Conteúdo** (5 blocos)
Elementos de texto:
- **Título Grande**: H2 destacado
- **Parágrafo**: Texto corrido
- **Citação**: Blockquote estilizado
- **Lista com Marcadores**: Lista de itens
- **Card Destaque**: Caixa de conteúdo

### 🖼️ **Mídia** (3 blocos)
Elementos visuais:
- **Imagem Completa**: Imagem full-width
- **Imagem + Texto**: Layout 50/50
- **Card de Produto**: Produto com imagem, descrição e preço

### 🎯 **CTA (Call to Action)** (4 blocos)
Botões e chamadas:
- **Botão Primário**: CTA principal
- **Botão Outline**: CTA secundário
- **Box CTA Completo**: Seção destacada com CTA
- **Links Sociais**: Ícones de redes sociais

## 🎨 Paleta de Cores Utilizada

### Cores Principais
- **Primary**: `#4F46E5` (Indigo)
- **Warning**: `#F59E0B` (Amber)
- **White**: `#ffffff`

### Tons de Cinza
- **Gray-900**: `#1F2937` (Títulos)
- **Gray-600**: `#4B5563` (Texto)
- **Gray-500**: `#6B7280` (Texto secundário)
- **Gray-400**: `#9CA3AF` (Texto terciário)
- **Gray-100**: `#F9FAFB` (Background)
- **Gray-200**: `#E5E7EB` (Borders)

### Cores de Acentuação
- **Blue**: `#3B82F6`, `#DBEAFE`, `#E0E7FF`
- **Purple**: `#8B5CF6`, `#DDD6FE`
- **Pink**: `#EC4899`, `#FBCFE8`

## 🔧 Boas Práticas Implementadas

### 1. **Fontes Seguras**
```css
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
```
Fallback completo para diferentes sistemas operacionais.

### 2. **Imagens**
```html
<img src="url" alt="Descrição" width="600" style="width: 100%; max-width: 600px; height: auto; display: block; border: 0;">
```
- Sempre inclui `alt`
- `border: 0` para evitar bordas no Outlook
- `display: block` para evitar espaços extras

### 3. **Botões (Padrão Bulletproof)**
```html
<table role="presentation" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td style="border-radius: 6px; background-color: #4F46E5;">
            <a href="#" style="display: inline-block; padding: 14px 40px; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none;">Texto</a>
        </td>
    </tr>
</table>
```
Funciona em **todos** os clientes de email.

### 4. **Espaçamento Consistente**
- Padding externo: `40px`
- Padding interno: `20px-32px`
- Margens entre elementos: `16px-24px`

## 🌙 Suporte a Dark Mode

Os blocos usam cores que funcionam bem tanto em modo claro quanto escuro:
- Textos escuros (#1F2937) ficam legíveis em fundo branco
- Textos claros (#ffffff) ficam legíveis em fundos coloridos
- Cores de fundo (#F9FAFB) são neutras

**Nota**: Dark mode em emails é limitado e depende do cliente. Os blocos priorizam legibilidade universal.

## 📱 Compatibilidade

Testado e otimizado para:
- ✅ Gmail (Desktop & Mobile)
- ✅ Outlook (2007-2021, Office 365)
- ✅ Apple Mail (macOS, iOS)
- ✅ Yahoo Mail
- ✅ Thunderbird
- ✅ ProtonMail

## 🚀 Como Usar

1. **Arraste o bloco** da biblioteca para o editor
2. **Edite o conteúdo** diretamente no TiptapEditor
3. **Personalize**:
   - Altere textos
   - Mude cores (use hexadecimal)
   - Troque URLs de imagens
   - Atualize links dos botões

## ⚠️ Limitações de Email HTML

### Não Use:
- ❌ CSS Externo ou `<style>` tags
- ❌ JavaScript
- ❌ Vídeos embarcados (use thumbnail com link)
- ❌ SVG (use PNG/JPG)
- ❌ Flexbox/Grid (use tabelas)
- ❌ Position absolute/fixed
- ❌ Variáveis CSS (`var(--color)`)

### Use Sempre:
- ✅ Tabelas para layout
- ✅ Estilos inline
- ✅ Cores hexadecimal
- ✅ Fontes web-safe
- ✅ Imagens hospedadas (não base64)
- ✅ width/height explícitos em imagens

## 🎯 Dicas de Personalização

### Alterar Cores
Procure e substitua os valores hexadecimais:
```css
/* De */
background-color: #4F46E5;
color: #ffffff;

/* Para */
background-color: #10B981;
color: #ffffff;
```

### Alterar Fontes
Substitua o font-family por outra fonte segura:
```css
/* Opções seguras */
font-family: Georgia, serif; /* Serifada */
font-family: 'Courier New', monospace; /* Monoespaçada */
font-family: 'Trebuchet MS', sans-serif; /* Alternativa */
```

### Adicionar Mais Padding
```css
/* Aumentar espaçamento */
style="padding: 60px 40px;" /* Era 40px */
```

## 📚 Recursos Adicionais

- [Can I Email](https://www.caniemail.com/) - Compatibilidade de CSS em emails
- [Email on Acid](https://www.emailonacid.com/) - Testes de email
- [Litmus](https://www.litmus.com/) - Preview em múltiplos clientes
- [Really Good Emails](https://reallygoodemails.com/) - Inspiração

## 🔄 Próximas Melhorias

- [ ] Adicionar mais templates temáticos (e-commerce, eventos, etc.)
- [ ] Blocos para produtos específicos
- [ ] Variações de cor pré-definidas
- [ ] Blocos de tabelas de dados
- [ ] Templates sazonais (Natal, Black Friday, etc.)

---

**Desenvolvido para TribeSend** | Última atualização: 2024

