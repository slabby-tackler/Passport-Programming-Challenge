<template>
    <div class="row q-pt-md">
        <div class="offset-1 col-11">
            <div class="full-width non-selectable">
                root
                <q-menu context-menu>
                    <q-list>
                        <q-item clickable v-close-popup @click="showNewFactoryDialog=true">
                            New Factory
                        </q-item>
                    </q-list>
                </q-menu>
            </div>
            <factory v-for="factory of factories" 
                :key="factory.id" 
                :factory="factory" 
                @factoryUpdate="factoryUpdate"
                @factoryDelete="factoryDelete"
                @factoryChildren="factoryChildren" />
        </div>

        <q-dialog v-model="showNewFactoryDialog" @hide="cancelFactory">
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
        data() {
            return {
                factories: [],
                showNewFactoryDialog: false,
                newFactoryName: '',
                newFactoryRange: {
                    min: 0,
                    max: 1000
                },
            };
        },
        mounted() {
            Echo.channel('factory')
                .listen('FactoryCreate', e => {
                    this.factories.push(e.factory);
                })
                .listen('FactoryUpdate', e => {
                    this.factoryUpdate(e.factory);
                })
                .listen('FactoryDelete', e => {
                    this.factoryDelete(e.factory);
                });

            Echo.channel('child')
                .listen('ChildGenerate', e => {
                    this.factoryChildren(e);
                })

            console.log('app mounted.')
            axios.get('/factory')
            .then(response => {
                this.factories = response.data;
            })
            .catch(error => {
                this.$q.notify({
                    color: 'negative',
                    textColor: 'white',
                    icon: 'warning',
                    position: 'top',
                    message: 'There was an error retrieving factories',
                });
            })
        },
        methods: {
            saveFactory() {
                axios.post('/factory', { name: this.newFactoryName, rangeMin: this.newFactoryRange.min, rangeMax: this.newFactoryRange.max })
                .then(response => {
                    this.factories.push(response.data);

                    this.showNewFactoryDialog = false;

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
                        message: 'There was an error creating factory.',
                    });
                })
            },
            cancelFactory() {
                this.newFactoryName = '';
                this.newFactoryRange.min = 0;
                this.newFactoryRange.max = 1000;
            },
            factoryUpdate(factory) {
                let index = this.factories.findIndex(item => {
                    return item.id === factory.id;
                });

                let updateFactory = this.factories[index];

                updateFactory.name = factory.name;
                updateFactory.lower_bound = factory.lower_bound;
                updateFactory.upper_bound = factory.upper_bound;
            },
            factoryDelete(factory) {
                let index = this.factories.findIndex(item => {
                    return item.id === factory.id;
                });

                this.$delete(this.factories, index);
            },
            factoryChildren(data) {
                let factory = data.factory;
                let children = data.children;

                let index = this.factories.findIndex(item => {
                    return item.id === factory.id;
                });

                this.factories[index].children = children;
            }
        }
    }
</script>

<style>
    .dialog-card {
        min-width: 50%;
    }
</style>