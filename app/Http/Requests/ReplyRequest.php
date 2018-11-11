<?php

namespace App\Http\Requests;

class ReplyRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    // UPDATE ROLES
                    'content' => 'required|min:6',
                    'topic_id' => 'required|numeric'
                ];
            }
            // UPDATE
            case 'PUT':
            {
                return [
                    // UPDATE ROLES
                    'content' => 'required|min:6',
                    'topic_id' => 'required|numeric'
                ];
            }
            case 'PATCH':
            {
                return [
                    // UPDATE ROLES
                    'content' => 'required|min:6',
                    'topic_id' => 'required|numeric'
                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }
    }

    public function messages()
    {
        return [
            'content.min' => '内容必须至少六个字符',
        ];
    }
}
