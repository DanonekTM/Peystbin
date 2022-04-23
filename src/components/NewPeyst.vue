<template>
<div class="py-10 dark:bg-nqblack">

	<div v-if="error" aria-live="assertive" class="z-10 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="error" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-darkbnb">
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
								</svg>
							</div>
							<div v-if="error" class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Error!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									{{ errorMsg }}
								</p>
							</div>
							<div class="ml-4 flex-shrink-0 flex">
								<button @click="error = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-nqblack">
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

	<header>
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
				New Peyst
			</h1>
		</div>
	</header>

	<main>
		<form class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-on:submit.prevent="newPaste">

			<div class="px-4 py-8 sm:px-0">
				<span class="pl-2 pb-4 block text-sm font-medium text-warm-gray-900 dark:text-gray-100">Content</span>
				<ResizeAuto>
					<template v-slot:default="{resize}">
						<textarea required v-model="fields.content" @keydown.tab.prevent="tabHandler($event)" @input="resize" rows="20" class="border-transparent focus:border-transpart overflow-hidden outline-none border-none resize-none hover:border-transparent bg-white py-3 px-4 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb dark:text-gray-50"/>
					</template>
				</ResizeAuto>
			</div>

			<span class="pl-2 pb-4 block text-sm font-medium text-warm-gray-900 dark:text-gray-100">Settings</span>
			<div class="px-4 py-8 sm:px-0 bg-white block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
				<div class="px-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
					<div class="relative rounded-lg px-6 py-5 flex items-center space-x-3">
						<div class="flex-1 min-w-0">
							<div class="mt-1 relative rounded-md">
								<label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Title</label>
								<input v-model="fields.title" type="text" class="mt-1 block w-full pr-10 border-gray-300 text-grey-900 placeholder-grey-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" placeholder="Title" maxlength="32"/>
							</div>
						</div>
					</div>
					<div class="relative rounded-lg px-6 py-5 flex items-center space-x-3">
						<div class="flex-1 min-w-0">
							<div class="mt-1 relative rounded-md">
								<label for="expiry" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Expiration</label>
								<select v-model="fields.expiry" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white">
									<option value="1D">Expiration (1 day)</option>
									<option value="BURN">burn after reading</option>
									<option value="10M">10 minutes</option>
									<option value="1H">1 hour</option>
									<option value="1W">1 week</option>
									<option value="E">Eternal</option>
								</select>
							</div>
						</div>
					</div>
					<div class="relative rounded-lg px-6 py-5 flex items-center space-x-3">
						<div class="flex-1 min-w-0">
							<div class="mt-1 relative rounded-md">
								<label for="exposure" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Paste Exposure</label>
								<select v-model="fields.exposure" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white">
									<option value="PUBLIC">Public</option>
									<option value="UNLISTED">Unlisted</option>
									<option v-if="this.$store.getters['auth/isAuthenticated']" value="PRIVATE">Private</option>
								</select>
							</div>
						</div>
					</div>
					<div class="relative rounded-lg px-6 py-5 flex items-center space-x-3">
						<div class="flex-1 min-w-0">
							<div class="mt-1 relative rounded-md">
								<label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-100">Password</label>
								<div class="mt-1 relative rounded-md shadow-sm">
									<input v-model="fields.password" :type="showPass ? 'text' : 'password'" class="mt-1 block w-full pr-10 border-gray-300 text-grey-900 placeholder-grey-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" placeholder="Password (recommended)"/>
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
						</div>
					</div>
					<div class="relative rounded-lg px-6 py-5 flex items-center space-x-3">
						<div class="flex-1 min-w-0">
							<div v-if="this.$store.getters['auth/isAuthenticated']">
								<label class="inline-flex items-center">
									<input v-model="fields.guest" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded dark:text-indigo-500">
									<span class="ml-2 dark:text-gray-100">Paste as Guest</span>
								</label>
							</div>
						</div>
					</div>
					<div class="relative rounded-lg px-6 py-5 flex items-end space-x-3">
						<div class="flex-1 min-w-0">
							<button :disabled="isClicked" type="submit" class="float-right inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
								<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
								</svg>
								Create Peyst
							</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</main>
</div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import ResizeAuto from "@/components/mixins/TextArea.vue";
import UserService from '../services/userService';
import { Base64 } from 'js-base64';

export default defineComponent({
	components: { 
		ResizeAuto,
	},
	data() {
		return {
			fields: {
				expiry: '1D',
				exposure: 'PUBLIC',
				content: ''
			},
			showPass: false,
			error: false,
			errorMsg: '',
			isClicked: false,
			theme: localStorage.getItem("theme") || 'light'
		};
	},
	methods: {
		newPaste() {
			this.error = false;
			UserService.addPaste(this.fields).then(
				(response) => {
					this.isClicked = false;
					this.error = false;
					if (this.fields.password === null || this.fields.password === undefined)
					{
						this.$router.push({ name: 'paste-view', params: { pasteId: response.data.paste_uid } });
					}
					else
					{
						this.$router.push({ name: 'paste-view', params: { pasteId: response.data.paste_uid, pastePassword: Base64.encode(this.fields.password) } });
					}
				},
				(error) => {
					this.error = true;
					this.isClicked = false;
					this.errorMsg = error.response.data.details.message;
				}
			);
		},
		tabHandler(event) {
			if (event) {
				event.preventDefault()
				let startText = this.fields.content.slice(0, event.target.selectionStart)
				let endText = this.fields.content.slice(event.target.selectionStart)
				this.fields.content = `${startText}\t${endText}`
				event.target.selectionEnd = event.target.selectionStart + 1
			}
		}
	}
});
</script>