<template>
<div class="py-10">
	<header>
		<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
			<h1 class="text-3xl font-bold leading-tight text-gray-900 dark:text-gray-100">
				Recent Peysts
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
									<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Something went wrong. 😔</h1>
									<p class="mt-2 text-base text-gray-500 dark:text-gray-100">Please try again later.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="success && pastes !== null">
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
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase inline-flex tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 pb-1" viewBox="0 0 20 20" fill="currentColor">
														<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
													</svg>
													Owner
												</th>
												<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-100">
													<svg xmlns="http://www.w3.org/2000/svg" class="inline-flex h-5 w-5 mb-1" viewBox="0 0 20 20" fill="currentColor">
														<path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
														<path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
													</svg>
													Views
												</th>
											</tr>
										</thead>
										<tbody class="bg-white divide-y divide-gray-200 dark:bg-darkbnb dark:divide-nqblack">
											<tr v-for="paste in pastes" :key="paste.uid">
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
													<router-link :to="{ name: 'paste-view', params: { pasteId: paste.uid }}" class="text-blue-500 hover:underline dark:text-blue-400">{{ paste.title }}</router-link>
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ timestampToDateTimeConverter(paste.created) }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
													<router-link :to="{ name: 'paste-view', params: { pasteId: paste.uid }}" class="text-blue-500 hover:underline dark:text-blue-400">{{ paste.uid }}</router-link>
												</td> 
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ paste.nickname }}
												</td>
												<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-100">
													{{ paste.views }}
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="success && pastes == null">
					<div class="px-4 py-8 sm:px-0">
						<div class="border-transparent overflow-hidden outline-none border-none bg-white py-48 block w-full text-warm-gray-900 rounded-lg dark:bg-darkbnb">
							<div class="py-8">
								<div class="text-center">
									<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Nothing here, yet.. 🤨</h1>
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
import axios from 'axios';
import config from '@/config';
import { timestampToDateTimeConverter } from '@/utils/tools';

export default defineComponent({
	data() {
		return {
			loading: true,
			success: false,
			error: false,
			pastes: [],
		}
	},
	methods: {
		timestampToDateTimeConverter
	},
	created() {
		axios.get(config.baseAPI + 'recent_pastes').then(response => {
			this.success = true;
			this.pastes = response.data.pastes;
		}).catch(() => {
			this.error = true;
		}).finally(() => {
			this.loading = false;
		});
	}
});
</script>