<template>
<div class="py-10">

	<div v-if="error && showError" aria-live="assertive" class="z-20 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
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
				<span v-if="(loading || error || protected) && !fails.delete && !fails.edit">Paste Editor</span>
				<span v-else>Editing: {{ this.fields.title }}</span>
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
		<div v-if="error && !fails.delete && !fails.edit && !fails.password" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="px-4 py-8 sm:px-0">
				<div class="border-transparent overflow-hidden outline-none border-none bg-white py-48 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
					<div class="py-8">
						<div v-if="errorMsg.length === 0" class="text-center">
							<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Something went wrong. 😔</h1>
						</div>
						<div v-else class="text-center">
							<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">You are being rate limited. 👮</h1>
							<p class="mt-2 text-base text-gray-500 dark:text-gray-300">Please wait a moment and try again.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div v-if="success">
			<form class="max-w-7xl mx-auto sm:px-6 lg:px-8" v-on:submit.prevent="editPaste">
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
										<option value="DC">Don't change</option>
										<option value="BURN">burn after reading</option>
										<option value="10M">10 minutes</option>
										<option value="1H">1 hour</option>
										<option value="1D">1 day</option>
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
										<option value="DC">Don't change</option>
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
									<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
										<path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
									</svg>
									Edit Peyst
								</button>
							</div>
							<button type="button" class="float-right inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" @click="deletePaste(this.fields.paste_uid)">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
								</svg>
								Delete Paste
							</button>
						</div>
					</div>
				</div>
			</form>
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
import ResizeAuto from "@/components/mixins/TextArea.vue";
import UserService from '@/services/userService';

export default defineComponent({
	components: {
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot,
		ResizeAuto
	},
	data() {
		return {
			fields: {
				expiry: 'DC',
				exposure: 'DC'
			},
			paste_password: null,
			error: false,
			errorMsg: '',
			showError: null,
			loading: true,
			fails: {},
			protected: false,
			success: false,
			isClicked: false,
			showPass: false,
			theme: localStorage.getItem("theme") || 'light'
		};
	},
	methods: {
		timestampToDateTimeConverter,
		getPasteWithPassword() {
			this.isClicked = true;
			axios.post(config.baseAPI + 'get_paste_metadata', {
				paste_uid: this.$route.params.pasteId,
				password: this.paste_password
			}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} }).then(response => {
				this.paste = response.data;
				this.protected = false;
				this.success = true;
				this.pasteHasPasswordModal = false;
				this.fields.password = this.paste_password;
				this.fields.title = response.data.title;
				this.fields.content = response.data.content;
				this.fields.paste_uid = this.$route.params.pasteId;
			}).catch(error => {
				this.protected = true;
				this.error = true;
				this.fails.password = true;
				this.showError = true;
				this.errorMsg = error.response.data.details.message;
			}).finally(() => this.isClicked = false);
		},
		editPaste() {
			this.isClicked = true;
			this.error = false;
			UserService.updatePaste(this.fields).then(
				() => {
					this.isClicked = false;
					this.error = false;
					this.$router.push({ name: 'paste-view', params: { pasteId: this.fields.paste_uid } });
				},
				(error) => {
					this.error = true;
					this.isClicked = false;
					this.fails.edit = true;
					this.showError = true;
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
		},
		deletePaste(paste_uid) {
			axios(config.baseAPI + 'delete_paste', {
				method: 'DELETE',
				data: {
					"paste_uid": paste_uid
				},
				headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} 
			}).then(() => {
				this.$router.push({ name: 'my-pastes'});
			}).catch(error => {
				this.error = true;
				this.fails.delete = true;
				this.showError = true;
				this.errorMsg = error.response.data.details.message;
			});
		}
	},
	setup() {
		const pasteHasPasswordModal = ref(false);
		return { pasteHasPasswordModal };
	},
	created() {
		axios.post(config.baseAPI + 'get_paste_metadata', {
			paste_uid: this.$route.params.pasteId
		}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} }).then(response => {
			this.success = true;
			this.paste = response.data;
			this.fields.title = response.data.title;
			this.fields.content = response.data.content;
			this.fields.paste_uid = this.$route.params.pasteId;
		}).catch(error => {
			if (error.response?.data?.details?.code === 403) {
				this.protected = true;
			}
			else {
				this.error = true;
				this.showError = false;
				if (error.response?.data?.details?.code === 429) {
					this.errorMsg = error.response.data.details.message;
				}
			}
		}).finally(() => this.loading = false);
	}
});
</script>