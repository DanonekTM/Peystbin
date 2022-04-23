import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import Home from '../views/Home.vue';

const routes: Array<RouteRecordRaw> = [
	{
		path: '/',
		name: 'home',
		component: Home,
		meta: {
			title: "Home"
		}
	},
	{
		path: '/:pasteId/:pastePassword?',
		name: 'paste-view',
		component: () => import(/* webpackChunkName: "paste-view" */ '../views/PasteViewer.vue')
	},
	{
		path: '/recents',
		name: 'recents',
		meta: {
			title: "Recents"
		},
		component: () => import(/* webpackChunkName: "recents" */ '../views/Recents.vue')
	},
	{
		path: '/my-pastes',
		name: 'my-pastes',
		meta: {
			title: "My Peysts",
			authRequired: true,
		},
		component: () => import(/* webpackChunkName: "my-pastes" */ '../views/MyPastes.vue')
	},
	{
		path: '/paste-editor/:pasteId',
		name: 'paste-editor',
		meta: {
			title: "Paste Editor",
			authRequired: true,
		},
		component: () => import(/* webpackChunkName: "paste-editor" */ '../views/PasteEditor.vue')
	},
	{
		path: '/faq',
		name: 'faq',
		meta: {
			title: "FAQ"
		},
		component: () => import(/* webpackChunkName: "faq" */ '../views/FAQ.vue')
	},
	{
		path: '/login',
		name: 'login',
		meta: {
			title: "Login"
		},
		component: () => import(/* webpackChunkName: "login" */ '../views/Login.vue')
	},
	{
		path: '/forgot-password',
		name: 'forgot-password',
		meta: {
			title: "Forgotten Password"
		},
		component: () => import(/* webpackChunkName: "forgot-password" */ '../views/ForgotPassword.vue')
	},
	{
		path: '/confirm-email/:code',
		name: 'confirm-email',
		meta: {
			title: "Account Confirmation",
		},
		component: () => import(/* webpackChunkName: "confirm-email" */ '../views/ConfirmEmail.vue')
	},
	{
		path: '/reset-password/:code',
		name: 'reset-password',
		meta: {
			title: "Password Reset",
		},
		component: () => import(/* webpackChunkName: "reset-password" */ '../views/ResetPassword.vue')
	},
	{
		path: '/register',
		name: 'register',
		meta: {
			title: "Register"
		},
		component: () => import(/* webpackChunkName: "register" */ '../views/Register.vue')
	},
	{
		path: '/settings',
		name: 'settings',
		meta: {
			title: "Settings",
			authRequired: true,
		},
		component: () => import(/* webpackChunkName: "settings" */ '../views/Settings.vue')
	},
	{
		path: '/terms',
		name: 'terms',
		meta: {
			title: "Terms and Conditions",
		},
		component: () => import(/* webpackChunkName: "terms" */ '../views/TermsAndConditions.vue')
	},
	{
		path: '/:pathMatch(.*)*',
		name: 'error',
		meta: {
			title: "Error"
		},
		component: () => import(/* webpackChunkName: "error" */ '../views/Error.vue')
	}
];

const router = createRouter({
	history: createWebHistory(process.env.BASE_URL),
	routes
});

import config from '@/config';
import store from '@/store';

router.beforeEach((to, from ,next) => {
	if (to.meta.title) window.document.title = to.meta && to.meta.title ? config.projectName + " :: " + to.meta.title : config.projectName;

	if (to.meta.authRequired && !store.getters['auth/isAuthenticated']) {
		next({
			name: 'login'
		});
	}
	else if (to.name == 'paste-view' && to.params.pasteId.length != 13) {
		next({
			name: 'error'
		});
	}
	else if (to.name == 'paste-editor' && to.params.pasteId.length != 13) {
		next({
			name: 'error'
		});
	}
	else if (to.name == 'confirm-email' && to.params.code.length != 64) {
		next({
			name: 'error'
		});
	}
	else if (to.name == 'reset-password' && to.params.code.length != 60) {
		next({
			name: 'error'
		});
	}
	else {
		next();
	}
});

export default router;
