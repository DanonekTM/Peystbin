<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-52">
	<Navigation></Navigation>
	<Pastes></Pastes>
	<Footer></Footer>
	<ScrollToTop></ScrollToTop>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

import Navigation from '@/components/Navigation.vue';
import Pastes from '@/components/Pastes.vue';
import Footer from '@/components/Footer.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';

export default defineComponent({
	components: {
		Navigation,
		Pastes,
		Footer,
		ScrollToTop
	}
});
</script>
