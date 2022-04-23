<template>
<div class="py-10">

	<div v-if="errorShow" aria-live="assertive" class="z-10 fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
		<div class="w-full flex flex-col items-center space-y-4 sm:items-end">
			<transition enter-active-class="transform ease-out duration-300 transition" enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="errorShow" class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden dark:bg-darkbnb">
					<div class="p-4">
						<div class="flex items-start">
							<div class="flex-shrink-0">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
								</svg>
							</div>
							<div class="ml-3 w-0 flex-1 pt-0.5">
								<p class="text-sm font-medium text-gray-900 dark:text-gray-100">
									Error!
								</p>
								<p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
									{{ errorMsg }}
								</p>
							</div>
							<div class="ml-4 flex-shrink-0 flex">
								<button @click="errorShow = false" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-nqblack">
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
				My Peysts
			</h1>
		</div>
	</header>

	<main>
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			<div class="px-4 py-8 sm:px-0">
				<div v-if="loading">
					<div class="px-4 py-8 sm:px-0 sm:py-24 lg:py-46">
						<div class="border-transparent overflow-hidden outline-none border-none py-3 px-4 block w-full text-warm-gray-900 rounded-lg">
							<div class="flex items-center justify-center">
								<div class="material-spinner"><div></div><div></div><div></div><div></div></div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="error">
					<div class="px-4 py-8 sm:px-0">
						<div class="border-transparent overflow-hidden outline-none border-none bg-white py-48 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
							<div class="py-8">
								<div class="text-center">
									<div v-if="!ratelimit" class="text-center">
										<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Something went wrong. 😔</h1>
										<p class="mt-2 text-base text-gray-500 dark:text-gray-100">Please try again later.</p>
									</div>
									<div v-else class="text-center">
										<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">You are being rate limited. 👮</h1>
										<p class="mt-2 text-base text-gray-500 dark:text-gray-300">Please wait a moment and try again.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="success">
					<div class="flex flex-col">
						<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
							<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
								<div class="shadow overflow-hidden border-b border-gray-200 rounded-lg dark:border-nqblack">
									<table class="min-w-full divide-y divide-gray-200 dark:divide-nqblack">
										<thead class="bg-gray-50 dark:bg-darkgpl">
											<tr>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
														<path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
													</svg>
													Title
												</th> 
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
														<path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
													</svg>
													Posted
												</th>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
														<path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" />
													</svg>
													Unique ID
												</th>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
														<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
														<path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
													</svg>
													Views
												</th>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
													</svg>
													Expires
												</th>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
													</svg>
													Exposure
												</th>
												<th scope="col" class="relative px-6 py-3">
													<span class="sr-only">Edit</span>
												</th>
												<th scope="col" class="relative px-6 py-3">
													<span class="sr-only">Delete</span>
												</th>
											</tr>
										</thead>
										<tbody class="bg-white divide-y divide-gray-200 dark:bg-darkbnb dark:divide-nqblack">
											<tr v-for="(paste, index) in pastes" :key="paste.uid" v-bind:value="paste.uid">
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
													<router-link :to="{ name: 'paste-view', params: { pasteId: paste.uid }}" class="text-blue-500 hover:underline dark:text-blue-400">{{ paste.title }}</router-link>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ timestampToDateTimeConverter(paste.created) }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													<router-link :to="{ name: 'paste-view', params: { pasteId: paste.uid }}" class="text-blue-500 hover:underline dark:text-blue-400">{{ paste.uid }}</router-link>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ paste.views }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ paste.expiry }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ exposureConverter(paste.exposure) }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
													<router-link :to="{ name: 'paste-editor', params: { pasteId: paste.uid }}">
														<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
															<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
															</svg>
														</button>
													</router-link>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
													<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" @click="deletePaste(paste.uid, index)">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
														</svg>
													</button>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="noPastes && !error">
					<div class="px-4 py-8 sm:px-0">
						<div class="border-transparent overflow-hidden outline-none border-none bg-white py-48 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
							<div class="py-8">
								<div class="text-center">
									<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">No Peysts 🙁</h1>
									<p class="mt-2 text-base text-gray-500 dark:text-gray-100">Get started by creating one!</p>
									<div class="mt-6">
										<router-link :to="{ name: 'home' }" type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
											<svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
											</svg>
											New Peyst
										</router-link>
									</div>
								</div>
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
import { defineComponent } from 'vue';	
import UserService from '../services/userService';
import { timestampToDateTimeConverter, exposureConverter } from '@/utils/tools';
import axios from 'axios';
import config from '@/config';

const API_URL = config.baseAPI;

export default defineComponent({
	data() {
		return {
			loading: true,
			error: false,
			errorShow: false,
			ratelimit: false,
			success: false,
			errorMsg: '',
			errorPopupMsg: '',
			pastes: [],
			noPastes: false
		}
	},
	methods: {
		timestampToDateTimeConverter,
		exposureConverter,
		deletePaste(paste_uid, index) {

			axios(API_URL + 'delete_paste', {
				method: 'DELETE',
				data: {
					"paste_uid": paste_uid
				},
				headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} 
			}).then(response => {
				if (response?.data?.details?.code === 200) {
					this.pastes.splice(index, 1);
				}
			}).catch(error => {
				this.errorShow = true;
				this.errorMsg = error.response.data.details.message;
			}).finally(() => {
				if (this.pastes.length == 0)
				{
					this.noPastes = true;
					this.success = false;
				}
			});
		}
	},
	created() {
		UserService.getUsersPastes().then(pastes => {
				this.success = true;
				this.loading = false;
				this.pastes = pastes;
			},
			(error) => {
				this.error = true;
				this.loading = false;
				this.noPastes = true;
				this.errorMsg = error.response.data.details.message;
				this.errorShow = error.response?.data?.details?.code !== 429;
				this.ratelimit = error.response?.data?.details?.code === 429;
			}
		).finally(() => {
			if (this.pastes.length == 0)
			{
				this.noPastes = true;
				this.success = false;
			}
		});
	}
});
</script>