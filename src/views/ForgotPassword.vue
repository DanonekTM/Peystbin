<template>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 dark:bg-nqblack">

	<div v-if="success" aria-live="assertive" class="z-10 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="success" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-darkbnb">
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
								</svg>
							</div>
							<div class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Success!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									You should receive an email shortly!
								</p>
							</div>
							<div class="ml-4 flex-shrink-0 flex">
								<button @click="success = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-darkbnb">
								<span class="sr-only">Close</span>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
								</svg>
								</button>
							</div>
						</div>
					</div>
				</div>
			</transition>
		</div>
	</div>

	<div class="sm:mx-auto sm:w-full sm:max-w-md">
		<router-link :to="{ name: 'home'}"><img class="mx-auto h-12 w-auto" src="@/assets/img/logo.svg" alt="Peystbin" /></router-link>
		<h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-gray-100">
			Forgotten Password
		</h2>
		<p class="mt-2 text-center text-sm text-gray-600">
			<router-link :to="{ name: 'login'}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-blue-400 dark:hover:text-blue-500">
				Log in here!
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

	<form class="mt-8 sm:mx-auto sm:w-full sm:max-w-md" v-on:submit.prevent="forgotPassword">
		<div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 dark:bg-darkbnb">
			<div class="space-y-6">
				<div>
					<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
						Email address
					</label>
					<div class="mt-1">
						<input v-model="fields.email" id="email" name="email" type="email" autocomplete="email" required="" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
					</div>
				</div>

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
					<button :disabled="isClicked" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
						<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
						</svg>
						Email me a new password
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
			success: false,
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
		forgotPassword() {
			this.isClicked = true;
			this.error = false;
			this.success = false;
			AuthService.forgotPassword(this.fields).then(
				() => {
					this.isClicked = false;
					this.error = false;
					this.success = true;
					this.fields = {};
					this.recaptchaExpired();
				},
				(error) => {
					this.error = true;
					this.isClicked = false;
					this.errorMsg = error.response.data.details.message;
					this.recaptchaExpired();
				}
			);
		},
	}
});
</script>