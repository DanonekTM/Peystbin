<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-48">
	<Navigation></Navigation>
	<NewPeyst></NewPeyst>
	<Footer></Footer>
	<ScrollToTop></ScrollToTop>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

import Navigation from '@/components/Navigation.vue';
import NewPeyst from '@/components/NewPeyst.vue';
import Footer from '@/components/Footer.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';

export default defineComponent({
	components: {
		Navigation,
		NewPeyst,
		Footer,
		ScrollToTop
	}
});
</script>
