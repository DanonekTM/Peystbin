import axios from 'axios';
import config from '@/config';

const API_URL = config.baseAPI;

class UserService {

	getUserInformation() {
		return axios.get(API_URL + 'user', { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} });
	}
	
	getUsersPastes() {
		return axios(API_URL + 'my_pastes', {
			method: 'POST',
			headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} 
		}).then(response => {
			return response.data;
		});
	}

	addPaste(input) {
		return axios.post(API_URL + 'add_paste', {
			content: input.content,
			expiry: input.expiry,
			exposure: input.exposure,
			guest: input.guest,
			password: input.password,
			title: input.title,
			syntax: input.syntax
		}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} });
	}

	updatePaste(input) {
		return axios.post(API_URL + 'update_paste', {
			paste_uid: input.paste_uid,
			content: input.content,
			expiry: input.expiry,
			exposure: input.exposure,
			guest: input.guest,
			password: input.password,
			title: input.title,
			syntax: input.syntax
		}, { headers: {"Authorization" : 'Bearer ' + localStorage.getItem('AUTH_TOKEN')} });
	}
}

export default new UserService();