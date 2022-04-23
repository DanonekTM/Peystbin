<template>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 dark:bg-nqblack">
	<div class="sm:mx-auto sm:w-full sm:max-w-md">
		<router-link :to="{ name: 'home'}"><img class="mx-auto h-12 w-auto" src="@/assets/img/logo.svg" alt="Peystbin" /></router-link>
		<h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-gray-100">
			Log in to your account
		</h2>
		<p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-300">
			Or
			<router-link :to="{ name: 'register'}" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-blue-400 dark:hover:text-blue-500">
				register here!
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

	<form class="mt-8 sm:mx-auto sm:w-full sm:max-w-md" v-on:submit.prevent="handleLogin">
		<div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 dark:bg-darkbnb">
			<div class="space-y-6">
				<div>
					<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
						Email address
					</label>
					<div class="mt-1">
						<input v-model="fields.email" name="email" type="email" autocomplete="email" required="" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
					</div>
				</div>

				<div>
					<label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
					<div class="mt-1 relative rounded-md shadow-sm">
						<input v-model="fields.password" :type="showPass ? 'text' : 'password'" class="mt-1 block w-full pr-10 border-gray-300 text-grey-900 placeholder-grey-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white"/>
						<div @click="showPass = !showPass" v-bind:class="this.theme === 'light' ? 'absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer' : 'absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer svg-white'">
							<svg v-if="showPass == false" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
								<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
								<path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
							</svg>
							<svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
							<path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
							</svg>
						</div>
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

				<div class="text-sm">
					<router-link :to="{ name: 'forgot-password' }" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-blue-400 dark:hover:text-blue-500">
						Forgot your password?
					</router-link>
				</div>

				<div>
					<button :disabled="isClicked" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
						<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
							<path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
						</svg>
						Log in
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

export default defineComponent({
	components: {
		vueRecaptcha,
		ScrollToTop
	},
	data() {
		return {
			fields: {},
			error: false,
			showPass: false,
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
		handleLogin() {
			this.isClicked = true;
			this.$store.dispatch("auth/login", this.fields).then(
				() => {
					this.isClicked = false;
					this.error = false;
					this.$router.push("/");
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