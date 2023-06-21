<template>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <label for="example-filter">Example filter</label>
                <input
                    type="text"
                    name="example-filter"
                    v-model="exampleFilter"
                >
            </div>
            <div class="col-12">
                <button @click="filter">Filter</button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import axios from "axios";
import {ref} from "vue";

const exampleFilter = ref('')

const filter = (): void => {
    axios
        .post<unknown>('filtering-endpoint', {
            filters: [
                {
                    tag: 'example-filter',
                    value: exampleFilter.value
                }
            ]
        })
        .then(response => {
            // handle response
        })
}
</script>
