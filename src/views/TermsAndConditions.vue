<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-48">
	<Navigation></Navigation>
	<div>
		<div class="max-w-7xl mx-auto pt-12 px-4 sm:py-16 sm:px-6 lg:px-8">
			<div class="bg-white p-8 px-16 rounded-lg max-w-4xl mx-auto dark:bg-darkbnb">
				<h2 class="text-center pb-8 text-3xl font-extrabold text-gray-900 sm:text-4xl dark:text-gray-100">
					Terms and Conditions
				</h2>

				<h4 class="pb-2 text-xl font-extrabold text-gray-900 sm:text-xl dark:text-gray-100 ">
					General provisions
				</h4>

				<p class="text-gray-500 dark:text-gray-300">The administrator may change the content of the regulations at any time.</p>
				<p class="text-gray-500 dark:text-gray-300">The application only records data strictly necessary for the app to function.</p>
			</div>
		</div>
	</div>
	<ScrollToTop></ScrollToTop>
	<Footer></Footer>
</div>
</template>

<script>
import { defineComponent } from 'vue';
import Navigation from '@/components/Navigation.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';
import Footer from '@/components/Footer.vue';

export default defineComponent({
	components: {
		Navigation,
		Footer,
		ScrollToTop,
	},
});
</script>