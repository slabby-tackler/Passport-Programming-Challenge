<template>
	<div class="row q-pl-md">
        <div class="col-12">
        	<div class="full-width non-selectable">
            	{{ factory.name }}
            	<q-menu context-menu>
                    <q-list>
                        <q-item clickable v-close-popup @click="showFactoryEdit=true">
                            Edit Factory
                        </q-item>
                        <q-item clickable v-close-popup @click="generateChildren">
                        	{{ generateText }}
                        </q-item>
                        <q-item clickable v-close-popup @click="deleteFactory">
                        	Delete Factory
                        </q-item>
                    </q-list>
                </q-menu>
            </div>
            <child v-for="child of children" :key="child.id" :child="child" />
        </div>

        <q-dialog v-model="showFactoryEdit" @hide="cancelFactory">
            <q-card class="dialog-card bg-white">
                <q-card-section class="q-pa-none">
                    <div class="row items-center text-h6 bg-grey-4 text-grey-9 q-pa-sm">
                        <div class="col">
                            New Factory
                        </div>
                        <div class="col-auto text-right">
                            <q-btn round v-close-popup icon="clear" title="close" flat />
                        </div>
                    </div>
                </q-card-section>
                <q-card-section>
                    <div class="row">
                        <div class="col-12">
                            <q-input stack-label label="title" v-model="newFactoryName" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 offset-3 q-pt-lg">
                            <q-range :min="0" :max="1000" label-always :step="1" v-model="newFactoryRange" />
                        </div>
                    </div>
                    <div class="row q-pa-sm">
                        <div class="col-12 text-right">
                            <q-btn flat color="primary" label="cancel" title="cancel" v-close-popup />
                            <q-btn color="primary" label="save" title="save" @click="saveFactory" />
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>

<script>
	export default {
		props: ['factory'],
		data() {
			return {
				newFactoryName: this.factory.name,
				newFactoryRange: {
					min: this.factory.lower_bound,
					max: this.factory.upper_bound,
				},
				showFactoryEdit: false,
			}
		},
		computed: {
			children() {
				return this.factory.children
			},
			generateText() {
				if(this.children.length > 0) {
					return 'Regenerate Children';
				}

				return 'Generate Children';
			}
		},
		methods: {
			saveFactory() {
				axios.put(/factory/ + this.factory.id, {name: this.newFactoryName, rangeMin: this.newFactoryRange.min, rangeMax: this.newFactoryRange.max})
				.then(response => {
					this.$emit('factoryUpdate', response.data);

					this.showFactoryEdit = false;

					this.$q.notify({
                        color: 'positive',
                        textColor: 'white',
                        icon: 'check_circle',
                        position: 'top',
                        message: 'Factory successfully saved',
                    });
				})
				.catch(error => {
					this.$q.notify({
                        color: 'negative',
                        textColor: 'white',
                        icon: 'warning',
                        position: 'top',
                        message: 'There was an error updating factory.',
                    });
				})
			},
			cancelFactory() {
				this.newFactoryName = this.factory.name;
				this.newFactoryRange.min = this.factory.lower_bound;
				this.newFactoryRange.max = this.factory.upper_bound;
			},
			deleteFactory() {
				this.$q.dialog({
					title: 'Delete Factory',
					message: 'Are you sure you wish to delete this factory?',
					cancel: true,
				})
				.onOk(() => {
					axios.delete('/factory/' + this.factory.id)
					.then(response => {
						this.$emit('factoryDelete', response.data);

						this.$q.notify({
	                        color: 'positive',
	                        textColor: 'white',
	                        icon: 'check_circle',
	                        position: 'top',
	                        message: 'Factory successfully saved',
	                    });
					})
					.catch(error => {
						this.$q.notify({
	                        color: 'negative',
	                        textColor: 'white',
	                        icon: 'warning',
	                        position: 'top',
	                        message: 'There was an error updating factory.',
	                    });
					})
				})
				.onCancel(() => {
					// Nothing on cancel
				})
				
			},
			generateChildren() {
				this.$q.dialog({
					title: this.generateText,
					message: 'How many children do you want to generate (max 15).',
					prompt: {
						model: 'string',
						type: 'number',
					}
				})
				.onOk(value => {
					if(value > 15) {
						this.$q.notify({
	                        color: 'negative',
	                        textColor: 'white',
	                        icon: 'warning',
	                        position: 'top',
	                        message: 'Number of children must be less or equal to 15.',
	                    });
					} else {
						axios.put('/factory/' + this.factory.id + '/children', { numChildren: value })
						.then(response => {
							let data = {
								factory: this.factory,
								children: response.data,
							};

							this.$emit('factoryChildren', data);
						})
						.catch(error => {
							this.$q.notify({
		                        color: 'negative',
		                        textColor: 'white',
		                        icon: 'warning',
		                        position: 'top',
		                        message: 'There was an error generating children for this factory.',
		                    });
						})
					}
				})
			}
		}
	}
</script>