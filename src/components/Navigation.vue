<template>
<Disclosure as="nav" class="bg-white dark:bg-darkbnb shadow-sm" v-slot="{ open }">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<div class="flex justify-between h-16">
			<div class="flex">
				<div class="flex-shrink-0 flex items-center">
					<router-link :to="{ name: 'home' }">
						<img class="block lg:hidden h-8 w-auto" src="@/assets/img/logo-mini.svg" alt="Peystbin" />
						<img class="hidden lg:block h-8 w-auto" src="@/assets/img/logo.svg" alt="Peystbin" />
					</router-link>
				</div>
				<div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
					<router-link v-for="item in navigation" v-show="item.canView" :key="item.name" :to="{ name: item.route }" :class="[isCurrentRoute(item.route) ? 'border-indigo-500 text-gray-900 dark:text-white dark:border-indigo-500' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-300 dark:hover:text-gray-100', 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium']" :aria-current="isCurrentRoute(item.route) ? 'page' : undefined">{{ item.name }}</router-link>
				</div>
			</div>
			<div v-if="this.$store.getters['auth/isAuthenticated']" class="hidden sm:ml-6 sm:flex sm:items-center">
				<Menu as="div" class="ml-3 relative">
				<div>
					<MenuButton class="bg-white flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
					<span class="sr-only">Open user menu</span>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
					</svg>
					</MenuButton>
				</div> 
				<transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
					<MenuItems class="z-10 origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none dark:bg-nqblack dark:divide-darkgpl">
						<div class="px-4 py-3">
						<p class="text-sm dark:text-gray-100">
							{{ getNickname }}
						</p>
						<p class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">
							{{ getEmail }}
						</p>
						</div>
						<div class="py-1">
							<MenuItem v-slot="{ active }">
								<router-link :to="{ name: 'settings' }" :class="[active ? 'bg-gray-100 text-gray-900 dark:bg-darkgpl dark:text-gray-300' : 'text-gray-700 dark:text-gray-100', 'group flex items-center px-4 py-2 text-sm']">
									<svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
									Settings
								</router-link>
							</MenuItem>
						</div>
						<div class="py-1">
							<MenuItem v-slot="{ active }">
								<div v-on:click="logout" :class="[active ? 'bg-gray-100 text-gray-900 dark:bg-darkgpl dark:text-gray-300' : 'text-gray-700 dark:text-gray-100', 'group flex items-center px-4 py-2 text-sm cursor-pointer']">
									<svg xmlns="http://www.w3.org/2000/svg" class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
									</svg>
									Logout
								</div>
							</MenuItem>
						</div>
					</MenuItems>
					</transition>
				</Menu>
			</div>
			<div v-else class="hidden sm:ml-6 sm:flex sm:items-center">
				<div class="mt-4 flex-shrink-0 flex md:mt-0 md:ml-4">
					<router-link :to="{ name: 'login' }" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
						Login
					</router-link>
					<router-link :to="{ name: 'register' }" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
						Register
					</router-link>
				</div>
			</div>
			<div class="-mr-2 flex items-center sm:hidden">
				<DisclosureButton class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-nqblack dark:text-gray-100">
				<span class="sr-only">Open main menu</span>
				<svg v-if="!open" xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
				</svg>
				<svg v-else xmlns="http://www.w3.org/2000/svg" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
				</svg>
				</DisclosureButton>
			</div>
		</div>
	</div>

	<DisclosurePanel class="sm:hidden">
		<div class="pt-2 pb-3 space-y-1">
			<router-link v-for="item in navigation" v-show="item.canView" :key="item.name" :to="{ name: item.route }" :class="[isCurrentRoute(item.route) ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:text-white dark:border-indigo-500 dark:bg-nqblack' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 dark:text-gray-300 dark:hover:text-gray-100 dark:hover:bg-nqblack', 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium']" :aria-current="isCurrentRoute(item.route) ? 'page' : undefined">{{ item.name }}</router-link>
		</div>
		<div v-if="this.$store.getters['auth/isAuthenticated']" class="pt-4 pb-3 border-t border-gray-200">
			<div class="flex items-center px-4">
				<div class="ml-3">
					<div class="text-base font-medium text-gray-800 dark:text-gray-300">{{ getNickname }}</div>
					<div class="text-sm font-medium text-gray-500 dark:text-gray-300">{{ getEmail }}</div>
				</div>
			</div>
			<div class="mt-3 space-y-1">
				<router-link :to="{ name: 'settings' }" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-100 dark:hover:text-gray-300 dark:hover:bg-nqblack"> 
					Settings
				</router-link>
				<div v-on:click="logout" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 cursor-pointer dark:text-gray-100 dark:hover:text-gray-300 dark:hover:bg-nqblack"> 
					Logout
				</div>
			</div>
		</div>
		<div v-else class="pt-4 pb-3 border-t border-gray-200">
			<div class="mt-3 space-y-1">
				<router-link :to="{ name: 'login' }" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"> 
					Login
				</router-link>
				<router-link :to="{ name: 'register' }" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"> 
					Register
				</router-link>
			</div>
		</div>
	</DisclosurePanel>
	</Disclosure>
</template>

<script lang="ts">
import { ref, defineComponent } from 'vue';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import store from '@/store';

export default defineComponent({	
	components: {
		Disclosure,
		DisclosureButton,
		DisclosurePanel,
		Menu,
		MenuButton,
		MenuItem,
		MenuItems
	},
	data() {
		return {
			navigation : [
				{ name: 'New Peyst', route: 'home', canView: true },
				{ name: 'Recent Peysts', route: 'recents', canView: true },
				{ name: 'My Peysts', route: 'my-pastes', canView: store.getters['auth/isAuthenticated'] },
				{ name: 'FAQ', route: 'faq', canView: true },
			]
		};
	},
	methods: {
		isCurrentRoute(route: string): boolean {
			return route === this.$route.name; 
		},
		logout(): void {
			this.$store.dispatch("auth/logout");
			this.navigation[2].canView = false;
			this.$router.push('/');
		}
	},
	setup() {
		const open = ref(false);
		return { open };
	},
	computed: {
		getNickname() {
			return this.$store.getters['auth/getNickname'];
		},
		getEmail() {
			return this.$store.getters['auth/getUserEmail'];
		}
	},
});

</script>