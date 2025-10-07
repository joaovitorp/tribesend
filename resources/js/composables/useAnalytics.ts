/**
 * Composable para tracking de eventos do Google Analytics 4
 */

declare global {
    interface Window {
        gtag?: (...args: any[]) => void;
        dataLayer?: any[];
    }
}

interface EventParams {
    [key: string]: any;
}

export function useAnalytics() {
    /**
     * Verifica se o gtag está disponível
     */
    const isGtagAvailable = (): boolean => {
        return typeof window !== 'undefined' && typeof window.gtag === 'function';
    };

    /**
     * Envia um evento personalizado para o GA4
     */
    const trackEvent = (eventName: string, params: EventParams = {}): void => {
        if (!isGtagAvailable()) {
            console.warn('Google Analytics não está disponível');
            return;
        }

        window.gtag!('event', eventName, params);
    };

    /**
     * Evento de lead/conversão (evento recomendado do GA4)
     * Use este evento quando usuário se inscreve, preenche formulário, etc.
     * 
     * @example trackLead({ method: 'waitlist_landing_page', value: 1 })
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events#generate_lead
     */
    const trackLead = (params: {
        value?: number;
        currency?: string;
        method?: string;
    } = {}): void => {
        trackEvent('generate_lead', {
            currency: params.currency || 'BRL',
            value: params.value || 0,
            method: params.method || 'form',
            ...params,
        });
    };

    /**
     * Evento de sign_up (evento recomendado do GA4)
     * Use quando usuário completa um cadastro/registro
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events#sign_up
     */
    const trackSignUp = (params: {
        method?: string;
    } = {}): void => {
        trackEvent('sign_up', {
            method: params.method || 'email',
            ...params,
        });
    };

    /**
     * Evento de login (evento recomendado do GA4)
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events#login
     */
    const trackLogin = (params: {
        method?: string;
    } = {}): void => {
        trackEvent('login', {
            method: params.method || 'email',
            ...params,
        });
    };

    return {
        isGtagAvailable,
        trackEvent,
        trackLead,
        trackSignUp,
        trackLogin,
    };
}

