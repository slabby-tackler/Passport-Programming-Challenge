<template>
	<div class="row q-pl-md">
	    <div class="col-12">
	        {{ child.name }}
	    </div>
	</div>
</template>

<script>
	export default {
		props: ['child'],
	}
</script>