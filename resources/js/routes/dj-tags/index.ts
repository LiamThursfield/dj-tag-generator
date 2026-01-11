import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/dj-tags',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::index
* @see app/Http/Controllers/DjTagController.php:9
* @route '/dj-tags'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/dj-tags/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::create
* @see app/Http/Controllers/DjTagController.php:22
* @route '/dj-tags/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\DjTagController::store
* @see app/Http/Controllers/DjTagController.php:32
* @route '/dj-tags'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/dj-tags',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DjTagController::store
* @see app/Http/Controllers/DjTagController.php:32
* @route '/dj-tags'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\DjTagController::store
* @see app/Http/Controllers/DjTagController.php:32
* @route '/dj-tags'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DjTagController::store
* @see app/Http/Controllers/DjTagController.php:32
* @route '/dj-tags'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DjTagController::store
* @see app/Http/Controllers/DjTagController.php:32
* @route '/dj-tags'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
export const show = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/dj-tags/{dj_tag}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
show.url = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { dj_tag: args }
    }

    if (Array.isArray(args)) {
        args = {
            dj_tag: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        dj_tag: args.dj_tag,
    }

    return show.definition.url
            .replace('{dj_tag}', parsedArgs.dj_tag.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
show.get = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
show.head = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
const showForm = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
showForm.get = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\DjTagController::show
* @see app/Http/Controllers/DjTagController.php:39
* @route '/dj-tags/{dj_tag}'
*/
showForm.head = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\DjTagController::reprocess
* @see app/Http/Controllers/DjTagController.php:50
* @route '/dj-tags/{dj_tag}/reprocess'
*/
export const reprocess = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reprocess.url(args, options),
    method: 'post',
})

reprocess.definition = {
    methods: ["post"],
    url: '/dj-tags/{dj_tag}/reprocess',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\DjTagController::reprocess
* @see app/Http/Controllers/DjTagController.php:50
* @route '/dj-tags/{dj_tag}/reprocess'
*/
reprocess.url = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { dj_tag: args }
    }

    if (Array.isArray(args)) {
        args = {
            dj_tag: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        dj_tag: args.dj_tag,
    }

    return reprocess.definition.url
            .replace('{dj_tag}', parsedArgs.dj_tag.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\DjTagController::reprocess
* @see app/Http/Controllers/DjTagController.php:50
* @route '/dj-tags/{dj_tag}/reprocess'
*/
reprocess.post = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reprocess.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DjTagController::reprocess
* @see app/Http/Controllers/DjTagController.php:50
* @route '/dj-tags/{dj_tag}/reprocess'
*/
const reprocessForm = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reprocess.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\DjTagController::reprocess
* @see app/Http/Controllers/DjTagController.php:50
* @route '/dj-tags/{dj_tag}/reprocess'
*/
reprocessForm.post = (args: { dj_tag: string | number } | [dj_tag: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reprocess.url(args, options),
    method: 'post',
})

reprocess.form = reprocessForm

const djTags = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    reprocess: Object.assign(reprocess, reprocess),
}

export default djTags