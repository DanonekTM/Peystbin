<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-48">
	<Navigation></Navigation>
	<ControlPanel></ControlPanel>
	<Footer></Footer>
	<ScrollToTop></ScrollToTop>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

import Navigation from '@/components/Navigation.vue';
import ControlPanel from '@/components/ControlPanel.vue';
import Footer from '@/components/Footer.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';

export default defineComponent({
	components: {
		Navigation,
		ControlPanel,
		Footer,
		ScrollToTop
	}
});
</script>
