import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/voices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\VoiceController::index
* @see app/Http/Controllers/VoiceController.php:9
* @route '/voices'
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

const VoiceController = { index }

export default VoiceController