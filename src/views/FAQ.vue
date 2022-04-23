<template>
<div class="min-h-screen bg-gray-100 dark:bg-nqblack pb-48">
	<Navigation></Navigation>
	<div>
		<div class="max-w-7xl mx-auto pt-12 px-4 sm:py-16 sm:px-6 lg:px-8">
			<div class="bg-white p-8 px-16 rounded-lg max-w-4xl mx-auto divide-y-2 divide-gray-200 dark:bg-darkbnb dark:divide-greyple">
				<h2 class="text-center text-3xl font-extrabold text-gray-900 sm:text-4xl dark:text-gray-100">
					Frequently asked questions
				</h2>
				<dl class="mt-6 space-y-6 divide-y divide-gray-200 dark:divide-greyple">
					<Disclosure as="div" v-for="faq in faqs" :key="faq.question" class="pt-6" v-slot="{ open }">
						<dt class="text-lg">
							<DisclosureButton class="text-left w-full flex justify-between items-start text-gray-400 dark:text-gray-100">
								<span class="font-medium text-gray-900 dark:text-gray-100">
									{{ faq.question }}
								</span>
								<span class="ml-6 h-7 flex items-center">
									<svg xmlns="http://www.w3.org/2000/svg" :class="[open ? '-rotate-180' : 'rotate-0', 'h-6 w-6 transform']" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
									</svg>
								</span>
							</DisclosureButton>
						</dt>
						<DisclosurePanel as="dd" class="mt-2 pr-12">
							<p v-html="faq.answer" class="text-base text-gray-500 dark:text-gray-300"></p>
						</DisclosurePanel>
					</Disclosure>
				</dl>
			</div>
			<div class="mt-6 text-center text-xs dark:text-gray-100">
				Last Updated: {{ this.lastUpdated }}
			</div>
		</div>
	</div>
	<ScrollToTop></ScrollToTop>
	<Footer></Footer>
</div>
</template>

<script>
import { ref, defineComponent } from 'vue';
import Navigation from '@/components/Navigation.vue';
import ScrollToTop from '@/components/ScrollToTop.vue';
import Footer from '@/components/Footer.vue';
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';

export default defineComponent({
	components: {
		Navigation,
		Footer,
		ScrollToTop,
		Disclosure,
		DisclosureButton,
		DisclosurePanel
	},
	setup() {
		const open = ref(false);
		return {
			faqs: [
				{
					question: "What is Peystbin?",
					answer: "Peystbin is a website that allows you to save any text for simple distribution. Although the website is mostly used by programmers to store source code and configuration information, anyone is welcome to paste any type of material. The site's goal is to make it easier for users to share vast amounts of text over the internet.",
				},
				{
					question: "How many pastes can I create?",
					answer: "As many as you like!",
				},
				{
					question: "Who can see my pastes?",
					answer: "If you make a public paste (public by default), it will appear in the recent peysts tab for everyone to see. You can also make unlisted pastes; however, unless you share your paste URL, these pastes will be inaccessible to others. If you're a Peystbin member, you can also make private pastes that can be only accessed by you!",
				},
				{
					question: "What is the maximum paste size?",
					answer: "A paste can have a maximum size of 500 kilobytes (0.5 megabytes). This should be enough for almost any script, and it keeps users from overloading our servers.",
				},
				{
					question: "What encryption do we use?",
					answer: "Pastes with a password are encrypted with AES-256 and hashed using SHA-512.",
				},
				{
					question: "How can I contact the webmaster?",
					answer: "Simple, just email <a class='text-indigo-500' href='mailto:admin@danonek.dev'>admin@danonek.dev</a>.",
				},
			],
			lastUpdated: '10/04/2022',
			open
		}
	},
});
</script>