<template>
<div class="min-h-screen pt-16 pb-12 flex flex-col bg-white dark:bg-nqblack">
	<main class="flex-grow flex flex-col justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex-shrink-0 flex justify-center">
			<router-link :to="{ name: 'home' }" class="inline-flex">
				<span class="sr-only">Peystbin</span>
				<img class="h-12 w-auto" src="@/assets/img/logo.svg" alt="Peystbin" />
			</router-link>
		</div>
		<div class="py-8">
			<div class="text-center">
				<p class="text-sm font-semibold text-indigo-600 tracking-wide dark:text-indigo-500">ERROR 404</p>
				<h1 class="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl dark:text-gray-100">Page not found.</h1>
				<p class="mt-2 text-base text-gray-500 dark:text-gray-300">Sorry, we couldn’t find the page you’re looking for.</p>
				<div class="mt-6">
					<router-link :to="{ name: 'home' }" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-indigo-500">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 pr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
						</svg> 
						Go back home 
					</router-link>
				</div>
			</div>
		</div>
	</main>
</div>
</template>