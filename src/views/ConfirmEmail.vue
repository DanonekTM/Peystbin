<template>
	<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 dark:bg-nqblack">
		<div class="sm:mx-auto sm:w-full sm:max-w-md">
			<router-link :to="{ name: 'home'}"><img class="mx-auto h-12 w-auto" src="@/assets/img/logo.svg" alt="Peystbin" /></router-link>
			<h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-gray-100">
				Confirm your account!
			</h2>
			<p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
				Or
				<router-link :to="{ name: 'login'}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-blue-400 dark:hover:text-blue-500">
					log in here!
				</router-link>
			</p>


			<div v-if="error" class="rounded-md bg-red-50 p-4 mt-10">
				<div class="flex">
					<div class="flex-shrink-0">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
						</svg>
					</div>
					<div class="ml-3">
						<p class="text-sm font-medium text-red-800">
							{{ this.errorMsg }}
						</p>
					</div>
					<div class="ml-auto pl-3">
						<div class="-mx-1.5 -my-1.5">
							<button @click="error = false" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
								<span class="sr-only">Dismiss</span>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
								</svg>
							</button>
						</div>
					</div>
				</div>
			</div>

		</div>

		<form class="mt-8 sm:mx-auto sm:w-full sm:max-w-md" v-on:submit.prevent="confirmEmail">
			<div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 dark:bg-darkbnb">
				<div class="space-y-6">

					<div>
						<label for="recaptcha" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
							Captcha
						</label>
						<div class="mt-1">
							<vueRecaptcha
								:siteKey="captchaKey"
								:theme="theme"
								@verified="recaptchaVerified"
								@expired="recaptchaExpired"
								@failed="recaptchaFailed"
								ref="vueRecaptcha"
							>
							</vueRecaptcha>
						</div>
					</div>

					<div>
						<div class="relative">
							<div class="absolute inset-0 flex items-center">
								<div class="w-full border-t border-gray-300"></div>
							</div>
							<div class="relative flex justify-center text-sm">
								<span class="px-2 bg-white text-gray-500"></span>
							</div>
						</div>
					</div>

					<div>
						<button :disabled="isClicked" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
							<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
								<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
							</svg>
							Confirm My Account
						</button>
					</div>
				</div>
			</div>
		</form>
		<ScrollToTop></ScrollToTop>
	</div>
</template>

<script lang="ts">
import vueRecaptcha from '@/utils/captcha';
import { defineComponent } from 'vue';
import ScrollToTop from '@/components/ScrollToTop.vue';
import config from '@/config';
import AuthService from '../services/authService';

export default defineComponent({
	components: {
		vueRecaptcha,
		ScrollToTop
	},
	data() {
		return {
			fields: {},
			error: false,
			errorMsg: '',
			isClicked: false,
			captchaKey: config.reCaptchaSiteKey,
			theme: localStorage.getItem("theme") || 'light'
		};
	},
	methods: {
		recaptchaVerified(response) {
			this.fields.captcha = response;
		},
		recaptchaExpired() {
			this.$refs.vueRecaptcha.reset();
		},
		recaptchaFailed() {
			this.error = true;
			this.errorMsg = "Captcha Failed!";
		},
		confirmEmail() {
			this.isClicked = true;
			AuthService.confirmEmail(this.fields).then(
				() => {
					this.isClicked = false;
					this.error = false;
					this.$router.push("/login");
				},
				(error) => {
					this.error = true;
					this.isClicked = false;
					this.errorMsg = error.response.data.details.message;
					this.recaptchaExpired();
				}
			);
		},
	},
	created() {
		this.fields.code = this.$route.params.code;
	}
});
</script>