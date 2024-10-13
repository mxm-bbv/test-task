import { useHead } from '@vueuse/head';

export function useDynamicHead(title) {
    useHead({
        title: `CLOUD — ${title}`,
    });
}