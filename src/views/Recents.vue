<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-48">
	<Navigation></Navigation>
	<RecentPeysts></RecentPeysts>
	<Footer></Footer>
	<ScrollToTop></ScrollToTop>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

import Navigation from '@/components/Navigation.vue';
import RecentPeysts from '@/components/RecentPeysts.vue';
import Footer from '@/components/Footer.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';

export default defineComponent({
	components: {
		Navigation,
		RecentPeysts,
		Footer,
		ScrollToTop
	}
});
</script>
