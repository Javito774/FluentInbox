<?php

class EntityController {
    private string $uid;
    public function __construct(string $uid) {
        $this->uid = $uid;
    }
    public function find(ctx) {
        // TODO validate query
        // TODO sanitize query
        ($results, $pagination) = $API.service($this->uid).find($sanitizedQuery);
        // TODO sanitize output($results, ctx)
        // TODO transform response
    }
    public function findOne(ctx) {
        ($id) = ctx.params;
        // TODO validate query
        // TODO sanitize query
        $entity = $API.service($this->uid).findOne($id, $sanitizedQuery);
        // TODO sanitize output($entity, ctx)
        // TODO transform response
    }
    public function create(ctx) {
        // TODO validate query
        // TODO sanitize query
        // TODO Parsebody(ctx)
        // TODO sanitize inputData(body.data, ctx)
        $entity = $API.service($this->uid).create({
            ...sanitizedQuery,
            data: sanitizedInputData,
        });
        // TODO sanitize output($entity, ctx)
        // TODO transform response
    }
    public function update(ctx) {
        ($id) = ctx.params;
        // TODO validate query
        // TODO sanitize query
        // TODO Parsebody(ctx)
        // TODO sanitize inputData(body.data, ctx)
        $entity = $API.service($this->uid).update($id, {
            ...sanitizedQuery,
            data: sanitizedInputData,
        });
        // TODO sanitize output($entity, ctx)
        // TODO transform response
    }
    public function delete(ctx) {
        ($id) = ctx.params;
        // TODO validate query
        // TODO sanitize query
        $entity = $API.service($this->uid).delete($id, $sanitizedQuery);
        // TODO sanitize output($entity, ctx)
        // TODO transform response
    }
}

?>
