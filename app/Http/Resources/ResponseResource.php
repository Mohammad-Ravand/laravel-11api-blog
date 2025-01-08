<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        // Check if it's an error or success response
        if (isset($this->resource['error'])) {
            return $this->errorResponse();
        }

        return $this->successResponse();
    }

    /**
     * Format the success response.
     *
     * @return array
     */
    private function successResponse()
    {
        return [
            'status' => 'success',
            'message' => $this->resource['message'] ?? 'Operation completed successfully',
            'data' => $this->resource['data'] ?? null,
        ];
    }

    /**
     * Format the error response.
     *
     * @return array
     */
    private function errorResponse()
    {
        return [
            'status' => 'error',
            'message' => $this->resource['message'] ?? 'An error occurred',
            'errors' => $this->resource['errors'] ?? null,
        ];
    }
}
