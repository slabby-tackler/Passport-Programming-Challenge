<template>
    <div class="row q-pt-md">
        <div class="offset-1 col-11">
            <div class="full-width">
                root
                <q-menu context-menu>
                    <q-list>
                        <q-item clickable v-close-popup @click="showNewFactoryDialog=true">
                            New Factory
                        </q-item>
                    </q-list>
                </q-menu>
            </div>
            <factory />
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
                            <q-btn flat color="primary" label="cancel" title="cancel" @click="cancelFactory" />
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
                showNewFactoryDialog: false,
                newFactoryName: '',
                newFactoryRange: {
                    min: 0,
                    max: 1000
                },
            };
        },
        mounted() {
            console.log('app mounted.')
        },
        methods: {
            saveFactory() {

            },
            cancelFactory() {
                this.newFactoryName = '';
                this.newFactoryRange.min = 0;
                this.newFactoryRange.max = 1000;
            }
        }
    }
</script>

<style>
    .dialog-card {
        min-width: 50%;
    }
</style>