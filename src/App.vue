<template>
	<router-view></router-view>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
	watch: {
		$route (to, from) {
			if ((from.name == 'register' || from.name == 'forgot' || from.name == 'login') 
				&& document.getElementById("recaptcha") != null) {
				document.head.removeChild(document.getElementById('recaptcha'));
				document.head.removeChild(document.querySelector('script[src*="recaptcha"]'));
			}
		}
	},
	created() {
		document.documentElement.className = localStorage.getItem("theme") || "light";
		if (this.isAuthenticated) {
			this.$store.commit('auth/updateDetails', this.$store.getters['auth/getAccessToken']);
		}
	},
	computed: {
		isAuthenticated() {
			return this.$store.getters['auth/isAuthenticated'];
		},
	}
});
</script>