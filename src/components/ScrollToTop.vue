<template>
<transition name="fade">
	<div class="cursor-pointer fixed right-0 bottom-0 m-4 z-50" v-show="scrollPosY > 300" @click="toTop">
		<div aria-expanded="false" class="bg-indigo-500 inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-indigo-600 focus:outline-none">
			<svg xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18" />
			</svg>
		</div>
	</div>
</transition>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({	
	data() {
		return {
			isScrolling: 0,
			scrollPosY: 0,
		}
	},
	mounted() {
		window.addEventListener('scroll', this.handleScroll);
	},
	methods: {
		handleScroll () {
			if (this.isScrolling) { return }
			this.isScrolling = setTimeout(() => {
				this.scrollPosY = window.scrollY;
				clearTimeout(this.isScrolling);
				this.isScrolling = 0;
			}, 100);
		},
		toTop() {
			window.scrollTo({
				top: 0,
				behavior: "smooth"
			});
		},
	}
});
</script>