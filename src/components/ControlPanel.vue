<template>
<div class="pt-10">
	<TransitionRoot as="deleteDialog" :show="isOpenDeleteAccountModal">
		<Dialog as="div" static class="fixed z-10 inset-0 overflow-y-auto" :open="isOpenDeleteAccountModal">
			<div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
				<TransitionChild as="deleteDialog" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
					<DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-nqblack dark:bg-opacity-75" />
				</TransitionChild>

				<span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
				<TransitionChild as="deleteDialog" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
					<form v-on:submit.prevent="deleteAccount" class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 dark:bg-darkbnb">
						<div class="hidden sm:block absolute top-0 right-0 pt-4 pr-4">
							<button type="button" class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-darkbnb" @click="isOpenDeleteAccountModal = false">
								<span class="sr-only">Close</span>
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
								</svg>
							</button>
						</div>
						<div class="sm:flex sm:items-start">
							<div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10 dark:bg-nqblack">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
								</svg>
							</div>
							<div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
								<DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
									Delete account
								</DialogTitle>
								<div class="mt-2">
									<p class="text-sm text-gray-500 dark:text-gray-300">
										Are you sure you want to delete your account? All of your data will be permanently removed from our servers forever. This action cannot be undone.
									</p>
									<div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-gray-200 pt-5 pb-5">
										<label for="delete_password" class="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 sm:pb-2 dark:text-gray-300">
											Your Password
										</label>
										<div class="mt-1 sm:mt-0 sm:col-span-2">
											<input v-model="fields.delete_password" type="password" name="delete_password" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md dark:bg-nqblack dark:placeholder-gray-400 dark:text-white dark:border-transparent" />
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
							<button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
								Delete Account
							</button>
							<button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm" @click="isOpenDeleteAccountModal = false">
								Cancel
							</button>
						</div>
					</form>
				</TransitionChild>
			</div>
		</Dialog>
	</TransitionRoot>

	<div v-if="success || error" aria-live="assertive" class="z-10 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="success || error" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-darkbnb">
					<div class="p-4">
						<div class="flex items-start">
							<div v-if="success" class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
								</svg>
							</div>
							<div v-else class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
								</svg>
							</div>
							<div v-if="success" class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Success!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									Password changed successfully!
								</p>
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
								<button @click="() => { success = false; error = false }" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-nqblack">
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
				Settings
			</h1>
		</div>
	</header>

	<main class="pt-10 pb-12 px-4 lg:pb-16 max-w-7xl mx-auto sm:px-6 lg:px-8">
		<div class="space-y-6">
			<div class="relative rounded-lg sm:rounded-lg border-b border-gray-200 bg-white px-6 py-5 shadow h-auto dark:bg-darkbnb dark:border-darkbnb">
				<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
					Account Information
				</h3>
				<p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
					Personal details and other.
				</p>
				<div class="mt-5 border-t border-gray-200">
					<dl class="sm:divide-y sm:divide-gray-200">
						<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
							<dt class="text-sm font-medium text-gray-700 dark:text-gray-300">
								Username
							</dt>
							<dd class="mt-1 text-sm text-right text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-300">
								{{ getNickname }}
							</dd>
						</div>
						<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
							<dt class="text-sm font-medium text-gray-700 dark:text-gray-300">
								Email Address
							</dt>
							<dd class="mt-1 text-sm text-right text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-300">
								{{ getEmail }}
							</dd>
						</div>
						<div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
							<dt class="text-sm font-medium text-gray-700 dark:text-gray-300">
								Join Date
							</dt>
							<dd class="mt-1 text-sm text-right text-gray-900 sm:mt-0 sm:col-span-2 dark:text-gray-300">
								{{ getJoinDate }}
							</dd>
						</div>
					</dl>
				</div>
			</div>

			<div class="relative rounded-lg sm:rounded-lg border-b border-gray-200 bg-white shadow h-auto mt-5 dark:bg-darkbnb dark:border-nqblack">
				<div class="px-4 py-5 sm:p-6">
					<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
						Dark Theme
					</h3>
					<div class="mt-2 sm:flex sm:items-start sm:justify-between">
						<div class="max-w-xl text-sm text-gray-500 dark:text-gray-300">
							<p> Let your eyes rest with dark mode or use light mode if you prefer. </p>
						</div>
						<div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
							<button @click="toggleTheme()" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-500">
								<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
								</svg>
								{{ this.theme == 'dark' ? 'Light Theme' : 'Dark Theme' }}
							</button>
						</div>
					</div>
				</div>
			</div>

			<div class="relative rounded-lg sm:rounded-lg border-b border-gray-200 bg-white px-6 py-5 shadow h-auto dark:bg-darkbnb dark:border-nqblack">
				<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
					Change Password
				</h3>
				<p class="mt-1 max-w-2xl text-sm text-gray-500 mb-6 dark:text-gray-300">
					A strong password helps to prevent unauthorized access to your account.
				</p>
				<form v-on:submit.prevent="changePassword">
					<div class="grid grid-cols-6 gap-6">
						<div class="col-span-6 sm:col-span-6 lg:col-span-2">
							<label for="old_password" class="pb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Old Password</label>
							<input v-model="fields.old_password" type="password" name="old_password" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
						</div>

						<div class="col-span-6 sm:col-span-3 lg:col-span-2">
							<label for="new_password" class="pb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
							<input v-model="fields.new_password" type="password" name="new_password" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
						</div>

						<div class="col-span-6 sm:col-span-3 lg:col-span-2">
							<label for="confirm_new_password" class="pb-1 block text-sm font-medium text-gray-700 dark:text-gray-300 bnb">Confirm New Password</label>
							<input v-model="fields.confirm_new_password" type="password" name="confirm_new_password" required class="max-w-lg block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:max-w-xs sm:text-sm border-gray-300 rounded-md dark:border-transparent dark:bg-nqblack dark:placeholder-gray-400 dark:text-white" />
						</div>
					</div>
					<div class="text-right pt-8">
						<button :disabled="isClicked" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:focus:ring-indigo-500">
							<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
							</svg>
							Set Password
						</button>
					</div>
					</form>
				</div>
			<div>

			<div class="relative rounded-lg sm:rounded-lg border-b border-gray-200 bg-white shadow h-auto mt-5 dark:bg-darkbnb dark:border-nqblack">
				<div class="px-4 py-5 sm:p-6">
					<h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
						Delete Account
					</h3>
					<div class="mt-2 sm:flex sm:items-start sm:justify-between">
						<div class="max-w-xl text-sm text-gray-500 dark:text-gray-300">
							<p> Once you delete your account, you will lose all data associated with it. </p>
						</div>
						<div class="mt-5 sm:mt-0 sm:ml-6 sm:flex-shrink-0 sm:flex sm:items-center">
							<button @click="isOpenDeleteAccountModal = true" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
								<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
								</svg>
								Delete Account
							</button>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
	</main>
</div>
</template>
<script lang="ts">
import { ref, defineComponent } from 'vue';
import AuthService from '../services/authService';
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

export default defineComponent({
	components: {
		Dialog,
		DialogOverlay,
		DialogTitle,
		TransitionChild,
		TransitionRoot
	},
	data() {
		return {
			fields: {},
			error: false,
			success: false,
			errorMsg: '',
			isClicked: false,
			theme: localStorage.getItem("theme") || 'light'
		};
	},
	methods: {
		changePassword() {
			this.isClicked = true;
			this.error = false;
			this.success = false;
			AuthService.changePassword(this.fields).then(() => {
					this.isClicked = false;
					this.error = false;
					this.fields = {};
					this.success = true;
				},
				(error) => {
					if (error.response.status === 401) {
						this.$store.dispatch("auth/refreshToken").then(
							() => {
								AuthService.changePassword(this.fields).then(() => {
									this.isClicked = false;
									this.error = false;
									this.fields = {};
									this.success = true;
								},
								() => {
									this.error = true;
									this.isClicked = false;
									this.errorMsg = error.response.data.details.message;
								});
							},
							(error) => {
								if (error.response.status === 400 || error.response.status === 403) {
									this.$store.dispatch("auth/logout");
									this.isClicked = false;
									this.$router.push('/');
								}
								this.error = true;
								this.isClicked = false;
								this.errorMsg = error.response.data.details.message;
							}
						);
					}
					else
					{
						this.error = true;
						this.isClicked = false;
						this.errorMsg = error.response.data.details.message;
					}
				}
			);
		},
		deleteAccount() {
			this.isOpenDeleteAccountModal = false;
			AuthService.deleteAccount(this.fields).then(() => {
					this.$store.dispatch("auth/logout");
					this.isClicked = false;
					this.$router.push('/');
				},
				(error) => {
					this.error = true;
					this.isClicked = false;
					this.errorMsg = error.response.data.details.message;
				}
			);
		},
		toggleTheme() {
			if (this.theme == "dark") {
				this.theme = "light";
				document.documentElement.className = 'light';
				localStorage.setItem("theme", "light");
			} else {
				this.theme = "dark";
				document.documentElement.className = 'dark';
				localStorage.setItem("theme", "dark");
			}
		}
	},
	setup() {
		const isOpenDeleteAccountModal = ref(false);
		return { isOpenDeleteAccountModal };
	},
	computed: {
		getNickname() {
			return this.$store.getters['auth/getNickname'];
		},
		getEmail() {
			return this.$store.getters['auth/getUserEmail'];
		},
		getJoinDate() {
			return this.$store.getters['auth/getJoinDate'];
		}
	},
});
</script>