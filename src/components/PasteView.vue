<template>
<div class="py-10">

	<div v-if="apiError" aria-live="assertive" class="z-20 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="apiError" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-darkbnb">
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
								</svg>
							</div>
							<div v-if="apiError" class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Error!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									{{ apiMsg }}
								</p>
							</div>
							<div class="ml-4 flex-shrink-0 flex">
								<button @click="apiError = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-nqblack">
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

	<div v-if="isCopied" aria-live="assertive" class="z-20 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="isCopied" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-nqblack">
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</div>
							<div class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Success!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									Paste content copied to your clipboard!
								</p>
							</div>
							<div class="ml-4 flex-shrink-0 flex">
								<button @click="isCopied = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-darkbnb">
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

	<TransitionRoot as="deleteDialog" :show="pasteHasPasswordModal">
		<Dialog as="div" static class="fixed z-10 inset-0 overflow-y-auto" :open="pasteHasPasswordModal">
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
				<TransitionChild as="deleteDialog" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
					<DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-nqblack dark:bg-opacity-75" />
				</TransitionChild>

				<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
				<TransitionChild as="deleteDialog" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
					<form v-on:submit.prevent="getPasteWithPassword" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 dark:bg-darkbnb">
						<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
							<button type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-darkbnb" @click="pasteHasPasswordModal = false">
								<span class="sr-only">Close</span>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
								</svg>
							</button>
						</div>
						<div class="sm:flex sm:items-start">
							<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-nqblack">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
								</svg>
							</div>
							<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
								<DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
									Locked Paste
								</DialogTitle>
								<div class="mt-2">
									<p class="text-sm text-gray-500 dark:text-gray-300">
										This paste is protected with a password, enter it to view the paste.
									</p>
									<div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200 pt-5 pb-5">
										<label for="delete_password" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 sm:pb-2 dark:text-gray-300">
											Paste Password
										</label>
										<div class="mt-1 sm:mt-0 sm:col-span-2">
											<input v-model="this.paste_password" type="password" name="delete_password" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
							<button :disabled="isClicked" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-600 text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:ml-3 sm:w-auto sm:text-sm">
								View Paste
							</button>
							<button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm" @click="pasteHasPasswordModal = false">
								Cancel
							</button>
						</div>
					</form>
				</TransitionChild>
			</div>
		</Dialog>
	</TransitionRoot>

	<header>
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
				<span v-if="loading || error || protected">Paste Viewer</span>
				<span v-else>{{ this.paste.title }}</span>
			</h1>
		</div>
	</header>

	<main>
		<div v-if="loading" class="max-w-7xl mx-auto sm:py-24 lg:py-52">
			<div class="px-4 py-8 sm:px-0">
				<div class="border-transparent overflow-hidden outline-none border-none py-3 px-4 block w-full text-warm-gray-900 rounded-lg">
					<div class="flex items-center justify-center ">
						<div class="material-spinner"><div></div><div></div><div></div><div></div></div>
					</div>
				</div>
			</div>
		</div>
		<div v-if="protected" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="px-4 py-8 sm:px-0">
				<div class="border-transparent overflow-hidden outline-none border-none bg-white py-60 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
					<div class="relative">
						<div class="absolute inset-0 flex items-center" aria-hidden="true">
							<div class="w-full border-t border-gray-300 dark:border-greyple"></div>
						</div>
						<div class="relative flex justify-center">
							<button @click="pasteHasPasswordModal = true" type="button" class="inline-flex items-center shadow-sm px-4 py-1.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:border-nqblack dark:bg-nqblack dark:text-gray-100">
								<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1.5 mr-1 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
								</svg>
								<span>Unlock</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div v-if="error" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="px-4 py-8 sm:px-0">
				<div class="border-transparent overflow-hidden outline-none border-none bg-white py-48 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
					<div class="py-8">
						<div v-if="errorMsg.length === 0" class="text-center">
							<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Paste not found.</h1>
							<p class="mt-2 text-base text-gray-500 dark:text-gray-300">It has either expired, been removed by its creator, or removed by one of the Peystbin staff.</p>
						</div>
						<div v-else class="text-center">
							<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">You are being rate limited. 👮</h1>
							<p class="mt-2 text-base text-gray-500 dark:text-gray-300">Please wait a moment and try again.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div v-if="success" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="px-4 py-8 sm:px-0">
				<div class="border-transparent overflow-hidden outline-none border-none bg-white py-4 px-4 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">

					<div class="lg:flex lg:items-center lg:justify-between">
						<div class="flex-1 min-w-0">
							<div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
								<div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
									<svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
									Created: {{ timestampToDateTimeConverter(this.paste.created) }}
								</div>
								<div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
									<svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
									</svg>
									Views: {{ this.paste.views }}
								</div>
								<div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
									<svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
									</svg>
									Expires: {{ this.paste.expiry }}
								</div>
								<div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
									<svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
									</svg>
									Size: {{ this.paste.size }}
								</div>
								<div class="flex items-center text-sm text-gray-500 dark:text-gray-300">
									<svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
									</svg>
									Author: {{ this.paste.owner_nickname }}
								</div>
							</div>
						</div>
						<div v-if="this.paste.owner_nickname === getNickname" class="mt-5 flex lg:mt-0 lg:ml-4">
							<router-link :to="{ name: 'paste-editor', params: { pasteId: this.$route.params.pasteId }}">
								<span class="sm:block">
									<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-600">
										<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
											<path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
										</svg>
										Edit
									</button>
								</span>
							</router-link>
						</div>
					</div>

					<div class="relative mt-6 mb-8">
						<div class="absolute inset-0 flex items-center" aria-hidden="true">
							<div class="w-full border-t border-gray-300 dark:border-greyple"></div>
						</div>
						<div class="relative flex justify-center">
							<button @click="copy(this.paste.content)" type="button" class="inline-flex items-center shadow-sm px-4 py-1.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:border-nqblack dark:bg-nqblack dark:text-gray-100">
								<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1.5 mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
								</svg>
								<span>Copy To Clipboard</span>
							</button>
						</div>
					</div>

					<div class="border-transparent focus:border-transpart overflow-hidden outline-none border-none resize-none hover:border-transparent pre-bg py-3 px-4 block w-full text-warm-gray-900 rounded-lg">
						<code class="text-white" style="white-space: pre-wrap; word-wrap: break-word;">{{ this.paste.content }}</code>
					</div>
				</div>
			</div>
		</div>
	</main>
</div>
</template>

<script lang="ts">
import { ref, defineComponent } from 'vue';
import config from '@/config';
import axios from 'axios';
import { timestampToDateTimeConverter } from '@/utils/tools';
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { Base64 } from 'js-base64';

export default defineComponent({
	components: {
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot,
	},
	data() {
		return {
			paste: {},
			paste_password: null,
			error: false,
			errorMsg: '',
			loading: true,
			protected: false,
			success: false,
			apiMsg: '',
			apiError: false,
			isClicked: false,
			isCopied: false
		};
	},
	methods: {
		timestampToDateTimeConverter,
		getPasteWithPassword() {
			this.isClicked = true;
			axios.post(config.baseAPI + 'get_paste', {
				paste_uid: this.$route.params.pasteId,
				password: this.paste_password
			}).then(response => {
				this.paste = response.data;
				this.protected = false;
				this.success = true;
				this.pasteHasPasswordModal = false;
				document.title = config.projectName + " :: " + this.paste.title;
				history.pushState({}, null, this.$route.params.pasteId + '/' + Base64.encode(this.paste_password));
			}).catch(error => {
				this.protected = true;
				this.apiError = true;
				this.apiMsg = error.response.data.details.message;
			}).finally(() => this.isClicked = false);
		},
		async copy(text) {
			await navigator.clipboard.writeText(text);
			this.isCopied = true;
		}
	},
	setup() {
		const pasteHasPasswordModal = ref(false);
		return { pasteHasPasswordModal };
	},
	created() {
		document.title = config.projectName;
		if (this.$route.params.pastePassword !== undefined && this.$route.params.pastePassword.length > 0)
		{
			let paste_password = Base64.decode(this.$route.params.pastePassword);
			axios.post(config.baseAPI + 'get_paste', {
				paste_uid: this.$route.params.pasteId,
				password: paste_password
			}).then(response => {
				this.success = true;
				this.paste = response.data;
				document.title = config.projectName + " :: " + this.paste.title;
			}).catch(error => {
				if (error.response?.data?.details?.code === 403) {
					this.protected = true;
				}
				else {
					this.error = true;
					if (error.response?.data?.details?.code === 429) {
						this.errorMsg = error.response.data.details.message;
					}
				}
			}).finally(() => this.loading = false);
		}
		else {
			axios.post(config.baseAPI + 'get_paste', {
				paste_uid: this.$route.params.pasteId
			}).then(response => {
				this.success = true;
				this.paste = response.data;

				document.title = config.projectName + " :: " + this.paste.title;
			}).catch(error => {
				if (error.response?.data?.details?.code === 403) {
					this.protected = true;
				}
				else {
					this.error = true;
					if (error.response?.data?.details?.code === 429) {
						this.errorMsg = error.response.data.details.message;
					}
				}
			}).finally(() => this.loading = false);
		}
		
	},
	computed: {
		getNickname() {
			return this.$store.getters['auth/getNickname'];
		},
	}
});
</script>