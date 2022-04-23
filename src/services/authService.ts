import axios from 'axios';
import config from '@/config';

const API_URL = config.baseAPI + 'auth/';

class AuthService {
	login(user) {
		return axios.post(API_URL + 'login', {
			email: user.email,
			password: user.password,
			captcha: user.captcha
		})
		.then(response => {
			if (response.data.token) {
				localStorage.setItem('AUTH_TOKEN', response.data.token);
			}
			return response.data.token;
		});
	}

	refreshToken() {
		return axios.post(API_URL + 'refresh').then(response => {
			if (response.data.token) {
				localStorage.setItem('AUTH_TOKEN', response.data.token);
			}
			return response.data.token;
		});
	}

	logout() {
		return axios.post(API_URL + 'logout')
		.then(response => {
			localStorage.removeItem('AUTH_TOKEN');
			return response.data;
		}).catch(() => {
			localStorage.removeItem('AUTH_TOKEN');
		});
	}

	register(user) {
		return axios.post(API_URL + 'register', {
			email: user.email,
			nickname: user.nickname,
			password: user.password,
			captcha: user.captcha,
			tos: user.tos
		});
	}

	confirmEmail(input: { captcha: string; code: string; }) {
		return axios.post(API_URL + 'confirm_email', {
			captcha: input.captcha,
			code: input.code
		});
	}

	resetPassword(input: { captcha: string; code: string; new_password: string; }) {
		return axios.post(API_URL + 'reset_password', {
			new_password: input.new_password,
			code: input.code,
			captcha: input.captcha,
		});
	}
	
	forgotPassword(input: { captcha: string; email: string; }) {
		return axios.post(API_URL + 'forgot_password', {
			captcha: input.captcha,
			email: input.email
		});
	}

	deleteAccount(input) {
		return axios.post(API_URL + 'delete_account', {
			password: input.delete_password
		}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} });
	}

	changePassword(input) {
		return axios.post(API_URL + 'change_password', {
			old_password: input.old_password,
			new_password: input.new_password,
			confirm_new_password: input.confirm_new_password,
		}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} });
	}
}

export default new AuthService();