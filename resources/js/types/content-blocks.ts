export interface ContentBlock {
    id: string;
    name: string;
    category: 'layout' | 'content' | 'media' | 'cta' | 'template';
    icon: string;
    html: string;
    preview: string;
}

export const contentBlocks: ContentBlock[] = [
    // ==================== TEMPLATES COMPLETOS ====================
    {
        id: 'email-newsletter',
        name: 'Newsletter Completa',
        category: 'template',
        icon: '📧',
        preview: 'Template completo de newsletter profissional',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0; padding: 0; background-color: #f4f4f4;">
    <tr>
        <td style="padding: 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff;">
                <!-- Header -->
                <tr>
                    <td style="padding: 40px 40px 30px; text-align: center; background-color: #4F46E5;">
                        <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Sua Newsletter</h1>
                    </td>
                </tr>
                <!-- Conteúdo -->
                <tr>
                    <td style="padding: 40px;">
                        <h2 style="margin: 0 0 16px; color: #1F2937; font-size: 24px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Bem-vindo!</h2>
                        <p style="margin: 0 0 16px; color: #4B5563; font-size: 16px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Aqui está sua newsletter mensal com as últimas novidades e atualizações.</p>
                    </td>
                </tr>
                <!-- Footer -->
                <tr>
                    <td style="padding: 30px 40px; background-color: #F9FAFB; text-align: center; border-top: 1px solid #E5E7EB;">
                        <p style="margin: 0 0 8px; color: #6B7280; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">© 2024 Sua Empresa</p>
                        <a href="#" style="color: #4F46E5; font-size: 12px; text-decoration: underline; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Cancelar inscrição</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'email-promo',
        name: 'Email Promocional',
        category: 'template',
        icon: '🎁',
        preview: 'Template de email promocional com destaque',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0; padding: 0; background-color: #FEF3C7;">
    <tr>
        <td style="padding: 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; background-color: #ffffff;">
                <tr>
                    <td style="padding: 60px 40px; text-align: center; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                        <h1 style="margin: 0 0 12px; color: #ffffff; font-size: 36px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">50% OFF</h1>
                        <p style="margin: 0 0 24px; color: #FEF3C7; font-size: 18px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Oferta exclusiva por tempo limitado!</p>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                            <tr>
                                <td style="border-radius: 6px; background-color: #ffffff;">
                                    <a href="#" style="display: inline-block; padding: 16px 40px; color: #D97706; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Aproveitar Agora</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px; text-align: center;">
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Válido até 31/12/2024. Não acumulativo com outras promoções.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== HERO SECTIONS ====================
    {
        id: 'hero-centered',
        name: 'Hero Centralizado',
        category: 'layout',
        icon: '🎯',
        preview: 'Banner hero com título e CTA',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 60px 40px; text-align: center; background-color: #4F46E5;">
            <h1 style="margin: 0 0 16px; color: #ffffff; font-size: 42px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.2;">Título Impactante</h1>
            <p style="margin: 0 0 24px; color: #E0E7FF; font-size: 18px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Subtítulo que explica sua proposta de valor</p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="border-radius: 6px; background-color: #ffffff;">
                        <a href="#" style="display: inline-block; padding: 14px 32px; color: #4F46E5; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Começar Agora</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'hero-image-bg',
        name: 'Hero com Imagem de Fundo',
        category: 'layout',
        icon: '🖼️',
        preview: 'Hero com imagem de fundo e overlay',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-image: url('https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200'); background-size: cover; background-position: center;">
    <tr>
        <td style="padding: 80px 40px; text-align: center; background-color: rgba(0, 0, 0, 0.6);">
            <h1 style="margin: 0 0 16px; color: #ffffff; font-size: 48px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.2; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">Transforme Seu Negócio</h1>
            <p style="margin: 0 0 32px; color: #E5E7EB; font-size: 20px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Soluções inovadoras para sua empresa crescer</p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="border-radius: 6px; background-color: #F59E0B;">
                        <a href="#" style="display: inline-block; padding: 16px 40px; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Saiba Mais</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== LAYOUT DE COLUNAS ====================
    {
        id: 'two-columns',
        name: 'Duas Colunas',
        category: 'layout',
        icon: '📐',
        preview: 'Layout responsivo de duas colunas',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 40px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="48%" style="padding: 20px; background-color: #F9FAFB; vertical-align: top;">
                        <h3 style="margin: 0 0 12px; color: #1F2937; font-size: 20px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Coluna 1</h3>
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Conteúdo da primeira coluna aqui.</p>
                    </td>
                    <td width="4%"></td>
                    <td width="48%" style="padding: 20px; background-color: #F9FAFB; vertical-align: top;">
                        <h3 style="margin: 0 0 12px; color: #1F2937; font-size: 20px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Coluna 2</h3>
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Conteúdo da segunda coluna aqui.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'three-features',
        name: 'Três Features',
        category: 'layout',
        icon: '📊',
        preview: 'Grid de três features com ícones',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 40px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="32%" style="padding: 20px; text-align: center; vertical-align: top;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="60" style="margin: 0 auto 16px;">
                            <tr>
                                <td style="width: 60px; height: 60px; border-radius: 50%; background-color: #DBEAFE; text-align: center; font-size: 28px; line-height: 60px;">⚡</td>
                            </tr>
                        </table>
                        <h4 style="margin: 0 0 8px; color: #1F2937; font-size: 18px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Rápido</h4>
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Performance otimizada</p>
                    </td>
                    <td width="2%"></td>
                    <td width="32%" style="padding: 20px; text-align: center; vertical-align: top;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="60" style="margin: 0 auto 16px;">
                            <tr>
                                <td style="width: 60px; height: 60px; border-radius: 50%; background-color: #DDD6FE; text-align: center; font-size: 28px; line-height: 60px;">🔒</td>
                            </tr>
                        </table>
                        <h4 style="margin: 0 0 8px; color: #1F2937; font-size: 18px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Seguro</h4>
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Proteção garantida</p>
                    </td>
                    <td width="2%"></td>
                    <td width="32%" style="padding: 20px; text-align: center; vertical-align: top;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="60" style="margin: 0 auto 16px;">
                            <tr>
                                <td style="width: 60px; height: 60px; border-radius: 50%; background-color: #FBCFE8; text-align: center; font-size: 28px; line-height: 60px;">💡</td>
                            </tr>
                        </table>
                        <h4 style="margin: 0 0 8px; color: #1F2937; font-size: 18px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Inteligente</h4>
                        <p style="margin: 0; color: #6B7280; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">IA avançada</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== CONTEÚDO ====================
    {
        id: 'heading-large',
        name: 'Título Grande',
        category: 'content',
        icon: '📝',
        preview: 'Título de seção grande e impactante',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 24px 40px;">
            <h2 style="margin: 0; color: #1F2937; font-size: 32px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; line-height: 1.3;">Título da Seção</h2>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'paragraph',
        name: 'Parágrafo',
        category: 'content',
        icon: '📄',
        preview: 'Parágrafo de texto bem formatado',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 20px;">
            <p style="margin: 0; color: #4B5563; font-size: 16px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'quote',
        name: 'Citação',
        category: 'content',
        icon: '💬',
        preview: 'Bloco de citação destacado',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="border-left: 4px solid #3B82F6; padding: 20px 24px; background-color: #EFF6FF;">
                        <p style="margin: 0; color: #1E40AF; font-size: 16px; font-style: italic; line-height: 1.6; font-family: Georgia, serif;">"Esta é uma citação importante que destaca um ponto chave da sua mensagem ou um depoimento de cliente."</p>
                        <p style="margin: 12px 0 0; color: #3B82F6; font-size: 14px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">— Nome do Autor</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'list-bullets',
        name: 'Lista com Marcadores',
        category: 'content',
        icon: '📋',
        preview: 'Lista de itens com bullets',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="20" style="padding: 0 12px 8px 0; vertical-align: top;"><span style="color: #3B82F6; font-size: 20px;">•</span></td>
                    <td style="padding: 0 0 8px; color: #4B5563; font-size: 16px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Primeiro item da lista</td>
                </tr>
                <tr>
                    <td width="20" style="padding: 0 12px 8px 0; vertical-align: top;"><span style="color: #3B82F6; font-size: 20px;">•</span></td>
                    <td style="padding: 0 0 8px; color: #4B5563; font-size: 16px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Segundo item da lista</td>
                </tr>
                <tr>
                    <td width="20" style="padding: 0 12px 0 0; vertical-align: top;"><span style="color: #3B82F6; font-size: 20px;">•</span></td>
                    <td style="padding: 0; color: #4B5563; font-size: 16px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Terceiro item da lista</td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'card-highlight',
        name: 'Card Destaque',
        category: 'content',
        icon: '🎴',
        preview: 'Card com conteúdo em destaque',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 32px; background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 8px;">
                        <h3 style="margin: 0 0 12px; color: #1F2937; font-size: 22px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Título do Card</h3>
                        <p style="margin: 0; color: #6B7280; font-size: 16px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Conteúdo do card com informações importantes. Perfeito para destacar ofertas ou informações especiais.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== MÍDIA ====================
    {
        id: 'image-full',
        name: 'Imagem Completa',
        category: 'media',
        icon: '🖼️',
        preview: 'Imagem responsiva em largura total',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 0 20px;">
            <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?w=1200&h=600&fit=crop" alt="Imagem descritiva" width="600" style="width: 100%; max-width: 600px; height: auto; display: block; border: 0;">
        </td>
    </tr>
</table>`,
    },

    {
        id: 'image-text',
        name: 'Imagem + Texto',
        category: 'media',
        icon: '🖼️',
        preview: 'Imagem ao lado de texto',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 40px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="48%" style="vertical-align: middle;">
                        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=400&h=300&fit=crop" alt="Descrição" width="280" style="width: 100%; max-width: 280px; height: auto; display: block; border-radius: 8px; border: 0;">
                    </td>
                    <td width="4%"></td>
                    <td width="48%" style="vertical-align: middle;">
                        <h3 style="margin: 0 0 12px; color: #1F2937; font-size: 22px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Título com Imagem</h3>
                        <p style="margin: 0; color: #6B7280; font-size: 16px; line-height: 1.6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Texto explicativo ao lado da imagem. Perfeito para destacar features ou benefícios.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'product-card',
        name: 'Card de Produto',
        category: 'media',
        icon: '🛍️',
        preview: 'Card de produto com imagem e preço',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 20px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 0; background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 8px; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&h=400&fit=crop" alt="Produto" width="600" style="width: 100%; max-width: 600px; height: auto; display: block; border: 0;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tr>
                                <td style="padding: 24px;">
                                    <h3 style="margin: 0 0 8px; color: #1F2937; font-size: 20px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Nome do Produto</h3>
                                    <p style="margin: 0 0 16px; color: #6B7280; font-size: 14px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Descrição breve do produto com principais características.</p>
                                    <p style="margin: 0 0 20px; color: #1F2937; font-size: 28px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">R$ 299,90</p>
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td style="border-radius: 6px; background-color: #4F46E5;">
                                                <a href="#" style="display: inline-block; padding: 12px 32px; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Comprar Agora</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== CTA (CALL TO ACTION) ====================
    {
        id: 'button-primary',
        name: 'Botão Primário',
        category: 'cta',
        icon: '🔘',
        preview: 'Botão de call-to-action principal',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 20px 40px; text-align: center;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="border-radius: 6px; background-color: #4F46E5;">
                        <a href="#" style="display: inline-block; padding: 14px 40px; color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Clique Aqui</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'button-outline',
        name: 'Botão Outline',
        category: 'cta',
        icon: '⭕',
        preview: 'Botão secundário com borda',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 20px 40px; text-align: center;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="border-radius: 6px; border: 2px solid #4F46E5; background-color: #ffffff;">
                        <a href="#" style="display: inline-block; padding: 12px 38px; color: #4F46E5; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Saiba Mais</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'cta-box',
        name: 'Box CTA Completo',
        category: 'cta',
        icon: '📦',
        preview: 'Caixa completa com CTA destacado',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 0 40px 40px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="padding: 40px; text-align: center; background-color: #4F46E5; border-radius: 8px;">
                        <h3 style="margin: 0 0 12px; color: #ffffff; font-size: 28px; font-weight: 700; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Pronto para Começar?</h3>
                        <p style="margin: 0 0 24px; color: #E0E7FF; font-size: 16px; line-height: 1.5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Junte-se a milhares de usuários satisfeitos hoje mesmo</p>
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                            <tr>
                                <td style="border-radius: 6px; background-color: #ffffff;">
                                    <a href="#" style="display: inline-block; padding: 16px 48px; color: #4F46E5; font-size: 16px; font-weight: 600; text-decoration: none; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Começar Agora</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'social-links',
        name: 'Links Sociais',
        category: 'cta',
        icon: '🔗',
        preview: 'Botões de redes sociais',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 32px 40px; text-align: center; background-color: #F9FAFB;">
            <p style="margin: 0 0 16px; color: #6B7280; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Siga-nos nas redes sociais</p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto;">
                <tr>
                    <td style="padding: 0 8px;">
                        <a href="#" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; background-color: #1DA1F2; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 20px;">𝕏</a>
                    </td>
                    <td style="padding: 0 8px;">
                        <a href="#" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; background-color: #0077B5; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600;">in</a>
                    </td>
                    <td style="padding: 0 8px;">
                        <a href="#" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; background-color: #E4405F; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 20px;">📷</a>
                    </td>
                    <td style="padding: 0 8px;">
                        <a href="#" style="display: inline-block; width: 40px; height: 40px; border-radius: 50%; background-color: #1877F2; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none; font-size: 20px; font-weight: 600;">f</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    // ==================== ELEMENTOS AUXILIARES ====================
    {
        id: 'divider',
        name: 'Divisor',
        category: 'layout',
        icon: '➖',
        preview: 'Linha divisória entre seções',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 32px 40px;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="border-top: 2px solid #E5E7EB; font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },

    {
        id: 'spacer',
        name: 'Espaçamento',
        category: 'layout',
        icon: '⬜',
        preview: 'Espaço em branco entre elementos',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="height: 40px; font-size: 0; line-height: 0;">&nbsp;</td>
    </tr>
</table>`,
    },

    {
        id: 'footer',
        name: 'Footer Completo',
        category: 'layout',
        icon: '🦶',
        preview: 'Rodapé com informações e links',
        html: `<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
    <tr>
        <td style="padding: 40px; background-color: #F9FAFB; border-top: 1px solid #E5E7EB;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td style="text-align: center; padding-bottom: 16px;">
                        <h3 style="margin: 0 0 8px; color: #1F2937; font-size: 20px; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Sua Empresa</h3>
                        <p style="margin: 0 0 16px; color: #6B7280; font-size: 14px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Rua Exemplo, 123 - São Paulo, SP</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; padding-bottom: 16px;">
                        <a href="#" style="color: #4F46E5; font-size: 14px; text-decoration: none; padding: 0 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Política de Privacidade</a>
                        <span style="color: #9CA3AF; padding: 0 4px;">|</span>
                        <a href="#" style="color: #4F46E5; font-size: 14px; text-decoration: none; padding: 0 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Termos de Uso</a>
                        <span style="color: #9CA3AF; padding: 0 4px;">|</span>
                        <a href="#" style="color: #4F46E5; font-size: 14px; text-decoration: none; padding: 0 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">Cancelar Inscrição</a>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <p style="margin: 0; color: #9CA3AF; font-size: 12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">© 2024 Sua Empresa. Todos os direitos reservados.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>`,
    },
];
