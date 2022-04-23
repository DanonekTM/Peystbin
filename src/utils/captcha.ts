/* eslint-disable  @typescript-eslint/no-explicit-any */
import { h, VNode } from 'vue';

declare let grecaptcha: any;
declare const window: any;

type captchaString = {
	recaptcha: string
}

export default {
	name: "vueRecaptcha",
	data(): captchaString {
		return {
			recaptcha: null
		}
	},
	props: {
		siteKey: {
			type: String,
			required: true
		},
		size: {
			type: String,
			required: false,
			default: "normal"
		},
		theme: {
			type: String,
			required: false,
			default: "light"
		}
	},
	emits: {
		verified: (response: string): boolean => {
			return response ? true : false;
		},
		expired: null,
		failed: null
	},
	methods: {
		renderRecaptcha(): void {
			try {
				this.recaptcha = grecaptcha.render(this.$refs.recaptcha, {
					'sitekey': this.siteKey,
					'theme': this.theme,
					'size': this.size,
					'tabindex': this.tabindex,
					'callback': (response: string) => this.$emit("verified", response),
					'expired-callback': () => this.$emit("expired"),
					'error-callback': () => this.$emit("failed")
				});
			}
			catch (rendered) {
				this.reset(); 
			}
		},
		execute(): void {
			grecaptcha.execute(this.recaptcha);
		},
		reset(): void {
			grecaptcha.reset(this.recaptcha);
		}
	},
	mounted(): void {
		if (window.grecaptcha != null) {
			this.renderRecaptcha();
		}
		
		new Promise<void>((resolve) => {
			window.recaptchaReady = function() {
				resolve();
			};

			const script = window.document.createElement('script');
			script.setAttribute("id", "recaptcha");
			script.async = true;
			script.defer = true;
			script.src = 'https://www.google.com/recaptcha/api.js?onload=recaptchaReady&render=explicit';
			window.document.head.appendChild(script);
		}).then(() => {
			this.renderRecaptcha();
		});
	},
	render(): VNode {
		return h('div', { ref: 'recaptcha' }, {});
	}
}