import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
export const edit = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/settings/api-keys',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
edit.url = (options?: RouteQueryOptions) => {
    return edit.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
edit.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
edit.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
const editForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
editForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::edit
* @see app/Http/Controllers/Settings/ApiKeysController.php:22
* @route '/settings/api-keys'
*/
editForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::update
* @see app/Http/Controllers/Settings/ApiKeysController.php:39
* @route '/settings/api-keys'
*/
export const update = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/settings/api-keys',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::update
* @see app/Http/Controllers/Settings/ApiKeysController.php:39
* @route '/settings/api-keys'
*/
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::update
* @see app/Http/Controllers/Settings/ApiKeysController.php:39
* @route '/settings/api-keys'
*/
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::update
* @see app/Http/Controllers/Settings/ApiKeysController.php:39
* @route '/settings/api-keys'
*/
const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ApiKeysController::update
* @see app/Http/Controllers/Settings/ApiKeysController.php:39
* @route '/settings/api-keys'
*/
updateForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

const apiKeys = {
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
}

export default apiKeys