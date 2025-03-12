<script setup lang="ts">
import VButton from '@/Components/Base/VButton.vue';
import { useI18n } from 'vue-i18n';

defineProps<{
    usersCount: number;
    transfersCount: number;
    sizeTransferred: number;
}>();

const { t } = useI18n();
</script>
<template>
    <div
        class="mx-auto flex min-h-dvh max-w-lg flex-col justify-center gap-12 px-2 pb-8 pt-40 text-lg"
    >
        <div>
            <h1 class="text-5xl font-bold">{{ t('clipboard') }}</h1>
            <p class="pl-1">{{ t('copy_content_across_devices_with_ease') }}</p>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl">{{ t('how_it_works') }}</h2>
            <ol class="list-inside list-decimal">
                <li>{{ t('login_on_device_a_and_paste_content') }}</li>
                <li>{{ t('login_on_device_b_then_copy_content') }}</li>
            </ol>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl">{{ t('quick_use') }}</h2>
            <p>
                {{ t('create_temporary_sessions_and_link_two_devices_tog') }}
            </p>
            <div class="flex flex-wrap gap-1">
                <VButton
                    :href="route('anon.register')"
                    class="flex-1 whitespace-nowrap"
                    variant="primary"
                >
                    {{ t('device_a_create_session') }}
                </VButton>
                <VButton
                    :href="route('anon.index')"
                    class="flex-1 whitespace-nowrap"
                    variant="primary"
                >
                    {{ t('device_b_link_session') }}
                </VButton>
            </div>
        </div>

        <div class="space-y-1">
            <h2 class="text-2xl">{{ t('recurrent_user') }}</h2>
            <div class="flex flex-wrap gap-1">
                <VButton
                    :href="route('register')"
                    class="flex-1"
                    variant="secondary"
                >
                    {{ t('register') }}
                </VButton>
                <VButton
                    :href="route('login')"
                    class="flex-1"
                    variant="secondary"
                >
                    {{ t('login') }}
                </VButton>
            </div>
        </div>

        <div class="mt-auto text-center text-base text-neutral-500">
            <p>
                {{
                    t('stats', {
                        usersCount,
                        sizeTransferred: Math.round(sizeTransferred / 1000),
                        transfersCount,
                    })
                }}
            </p>
        </div>
    </div>
</template>
