<template>
    <Suspense>

        <template #fallback>
            <div class="h-screen w-full flex justify-center items-center">
                <Spinner size="50px"/>
            </div>
        </template>

        <template #default>
            <slot/>
        </template>

    </Suspense>
</template>
        