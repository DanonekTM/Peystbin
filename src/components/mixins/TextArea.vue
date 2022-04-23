<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
	methods: {
		resize(event) {
			event.target.style.height = "auto";
			event.target.style.height = `${event.target.scrollHeight}px`;
		}
	},
	render() {
		return this.$slots.default({
			resize: this.resize
		});
	}
});
</script>