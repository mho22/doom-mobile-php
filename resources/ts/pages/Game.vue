<script setup lang="ts">

import { onMounted } from 'vue';
import type { DosPlayerOptions, DosPlayerFactoryType } from '@/public/jsdos/types/src/player';

declare const Dos : DosPlayerFactoryType;


onMounted( () =>
{
    emulators.pathPrefix = 'js-dos/';

    const root : HTMLDivElement = document.getElementById( 'js-dos' ) as HTMLDivElement;

    const options : DosPlayerOptions = { style : 'none', noSideBar : true, noFullscreen : true, noSocialLinks : true };

    Dos( root, options ).run( 'https://cdn.dos.zone/custom/dos/doom.jsdos' );
});

</script>

<template>

    <div id="js-dos" class="w-screen h-screen bg-black" />

</template>

<style>

.emulator-options
{
    @apply hidden;
}

</style>
