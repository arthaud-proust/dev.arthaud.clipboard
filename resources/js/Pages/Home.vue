<script setup lang="ts">
import VButton from '@/Components/Base/VButton.vue';
import Media from '@/Components/Media/Media.vue';
import TextCardInput from '@/Components/Text/TextCardInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { MediaDto, TextDto } from '@/types/generated';
import { PlusIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, router } from '@inertiajs/vue3';
import { useFileDialog } from '@vueuse/core';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const props = defineProps<{
    texts: Array<TextDto>;
    medias: Array<MediaDto>;
    maxMediaCount: number;
    focusTextId?: TextDto['id'];
}>();

const { t } = useI18n();

const destroyedTextIds = ref<Array<TextDto['id']>>([]);
const saveText = (
    text: TextDto | Omit<TextDto, 'id' | 'updatedAt' | 'createdAt'>,
) => {
    if ('id' in text && text.id) {
        if (text.content === '') {
            return destroyText(text);
        }

        router.put(
            route('texts.update', text),
            {
                content: text.content,
            },
            {
                preserveState: false,
            },
        );
    } else {
        router.post(
            route('texts.store'),
            {
                content: text.content,
            },
            {
                preserveState: false,
            },
        );
    }
};

const destroyText = (text: TextDto) => {
    destroyedTextIds.value.push(text.id);
    router.delete(route('texts.destroy', text), {
        preserveState: false,
    });
};

const createMedia = (file: File) => {
    if (!canCreateMedia.value) return;

    router.post(
        route('medias.store'),
        {
            file,
        },
        {
            preserveState: false,
        },
    );
};

const destroyedMediaIds = ref<Array<MediaDto['id']>>([]);
const canCreateMedia = computed(
    () => props.medias.length < props.maxMediaCount,
);
const { open, onChange } = useFileDialog({
    accept: '*',
    directory: false,
});
onChange((files) => {
    const file = files?.item(0);
    if (!file) return;

    createMedia(file);
});

const destroyMedia = (media: MediaDto) => {
    destroyedMediaIds.value.push(media.id);
    router.delete(route('medias.destroy', media), {
        preserveState: false,
    });
};

const order = (obj: { updatedAt: string }) => Date.parse(obj.updatedAt) / 1000;

const handlePaste = (e: ClipboardEvent) => {
    const file = e.clipboardData?.files.item(0);
    if (file) {
        return createMedia(file);
    }
};
</script>

<template>
    <Head :title="t('clipboard')" />

    <AuthenticatedLayout>
        <div
            class="mx-auto flex max-w-lg flex-col gap-2 px-2 py-20"
            @paste="handlePaste"
        >
            <h1 class="mb-2 text-2xl">{{ t('clipboard') }}</h1>

            <article class="w-full flex-col">
                <TextCardInput
                    class="w-full"
                    @save="saveText"
                    :placeholder="t('copy_anything')"
                    :focused="!focusTextId || texts?.length === 0"
                />
            </article>

            <div class="flex items-center gap-4">
                <VButton
                    variant="tertiary"
                    :disabled="!canCreateMedia"
                    @click="() => open()"
                >
                    {{ t('select_file') }}

                    <PlusIcon class="size-5" />
                </VButton>
                <p :class="canCreateMedia || 'font-bold text-red-700'">
                    {{
                        t('media_uploaded', {
                            count: medias.length,
                            countMax: maxMediaCount,
                        })
                    }}
                </p>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2">
                <template v-for="media in medias">
                    <div
                        v-if="!destroyedMediaIds.includes(media.id)"
                        class="flex w-full"
                        :style="{ order: order(media) }"
                    >
                        <Media class="w-full" :media="media" />
                        <button
                            class="flex items-start p-2 text-black hover:text-red-700"
                            @click="() => destroyMedia(media)"
                        >
                            <XMarkIcon class="size-6" />
                        </button>
                    </div>
                </template>

                <template v-for="text in texts">
                    <article
                        v-if="!destroyedTextIds.includes(text.id)"
                        class="flex w-full"
                        :style="{ order: order(text) }"
                    >
                        <TextCardInput
                            :text="text"
                            class="w-full"
                            @save="saveText"
                            :focused="focusTextId === text.id"
                            copiable
                        />
                        <button
                            class="flex items-start p-2 text-black hover:text-red-700"
                            @click="() => destroyText(text)"
                        >
                            <XMarkIcon class="size-6" />
                        </button>
                    </article>
                </template>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
