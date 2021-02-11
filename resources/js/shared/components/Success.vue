<template>
    <div class="text-center error">
        <i class="fas fa-thumbs-up"></i>
        <h1 class="mt-4">Success!</h1>
        <h2>
            <slot>

            </slot>
        </h2>
    </div>
</template>

<style scoped>
    .error {
        color: #999;
        font-size: 10rem;
    }
</style>
