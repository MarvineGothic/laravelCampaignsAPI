<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CampaignsTest extends TestCase
{
    public function test_get_campaigns()
    {
        $response = $this->get('/api/campaigns');

        $response->assertStatus(200);
        $this->assertCount(10, $response['campaigns']);
    }

    public function test_get_campaigns_with_offset()
    {
        $response = $this->get('/api/campaigns?offset=20');

        $response->assertStatus(200);
        $this->assertCount(10, $response['campaigns']);
    }

    public function test_get_campaigns_with_offset_and_limit()
    {
        $response = $this->get('/api/campaigns?offset=10&limit=5');

        $response->assertStatus(200);
        $this->assertCount(5, $response['campaigns']);


        $response = $this->get('/api/campaigns?offset=10&limit=10');

        $response->assertStatus(200);
        $this->assertCount(10, $response['campaigns']);


        $response = $this->get('/api/campaigns?offset=10&limit=15');

        $response->assertStatus(200);
        $this->assertCount(10, $response['campaigns']);
    }

    public function test_get_campaigns_sorted_by_created_at()
    {
        $response = $this->get('/api/campaigns?offset=10&limit=10');

        $response->assertStatus(200);

        $prevDateTime = null;
        foreach ($response['campaigns'] as $campaign) {
            $nextDateTime = strtotime($campaign["created_at"]);
            if ($prevDateTime) {
                $this->assertLessThanOrEqual($nextDateTime, $prevDateTime);
            }
            $prevDateTime = $nextDateTime;
        }

        $response = $this->get('/api/campaigns?offset=10&limit=10&sort=desc');

        $response->assertStatus(200);

        $prevDateTime = null;
        foreach ($response['campaigns'] as $campaign) {
            $nextDateTime = strtotime($campaign["created_at"]);
            if ($prevDateTime) {
                $this->assertGreaterThanOrEqual($nextDateTime, $prevDateTime);
            }
            $prevDateTime = $nextDateTime;
        }
    }

    public function test_get_campaigns_sorted_by_author()
    {
        $response = $this->get('/api/campaigns?offset=10&limit=10&sortBy=author');

        $response->assertStatus(200);

        $prevAuthorId = 0;
        foreach ($response['campaigns'] as $campaign) {
            $nextAuthorId = $campaign["author"]["id"];
            if ($prevAuthorId) {
                $this->assertLessThanOrEqual($nextAuthorId, $prevAuthorId);
            }
            $prevAuthorId = $nextAuthorId;
        }

        $response = $this->get('/api/campaigns?offset=10&limit=10&sortBy=author&sort=desc');

        $response->assertStatus(200);

        $prevAuthorId = null;
        foreach ($response['campaigns'] as $campaign) {
            $nextAuthorId = $campaign["author"]["id"];
            if ($prevAuthorId) {
                $this->assertGreaterThanOrEqual($nextAuthorId, $prevAuthorId);
            }
            $prevAuthorId = $nextAuthorId;
        }
    }

    public function test_add_new_campaign_invalid_input()
    {
        $this->json("POST", '/api/campaigns',
        json_decode('{
                            "campaign_id": "841e8ed8-6bd3-3647-8187-9d30c0dd8f0a",
                            "author": 7,
                            "inputs": [
                                {
                                    "type": "campaign_name",
                                    "value": "est quod unde aut reprehenderit esse ut et labore non"
                                },
                                {
                                    "type": "",
                                    "value": "aut deleniti quasi consequatur voluptas non sint vel aliquam ab"
                                }
                            ]
                        }', true))
        ->assertStatus(400)
        ->assertJson(["error" => "Missing input type or value"]);

        $this->json("POST", '/api/campaigns',
            json_decode('{
                            "campaign_id": "841e8ed8-6bd3-3647-8187-9d30c0dd8f0a",
                            "author": 7,
                            "inputs": [
                                {
                                    "type": "campaign_name",
                                    "value": "est quod unde aut reprehenderit esse ut et labore non"
                                },
                                {
                                    "type": "channel",
                                    "value": ""
                                }
                            ]
                        }', true))
            ->assertStatus(400)
            ->assertJson(["error" => "Missing input type or value"]);
    }

    public function test_add_new_campaign_valid_input()
    {
        $this->json("POST", '/api/campaigns',
            json_decode('{
                            "campaign_id": "841e8ed8-6bd3-3647-8187-9d30c0dd8f0a",
                            "author": 7,
                            "inputs": [
                                {
                                    "type": "campaign_name",
                                    "value": "est quod unde aut reprehenderit esse ut et labore non"
                                },
                                {
                                    "type": "channel",
                                    "value": "aut deleniti quasi consequatur voluptas non sint vel aliquam ab"
                                },
                                {
                                    "type": "source",
                                    "value": "molestiae eveniet dolores reprehenderit incidunt sed perspiciatis exercitationem debitis libero"
                                },
                                {
                                    "type": "target_url",
                                    "value": "https://usfdoisudjl.com"
                                }
                            ]
                        }', true))
            ->assertStatus(201);
    }

    public function test_add_new_campaign_same_campaign_id()
    {
        $this->json("POST", '/api/campaigns',
            json_decode('{
                            "campaign_id": "841e8ed8-6bd3-3647-8187-9d30c0dd8f0a",
                            "author": 7,
                            "inputs": [
                                {
                                    "type": "campaign_name",
                                    "value": "est quod unde aut reprehenderit esse ut et labore non"
                                },
                                {
                                    "type": "channel",
                                    "value": "aut deleniti quasi consequatur voluptas non sint vel aliquam ab"
                                },
                                {
                                    "type": "source",
                                    "value": "molestiae eveniet dolores reprehenderit incidunt sed perspiciatis exercitationem debitis libero"
                                },
                                {
                                    "type": "target_url",
                                    "value": "https://usfdoisudjl.com"
                                }
                            ]
                        }', true))
            ->assertStatus(400)
            ->assertJson(["error" => "Not valid"]);
    }

    public function test_get_existing_campaign_by_id()
    {
        $response = $this->get("/api/campaigns/841e8ed8-6bd3-3647-8187-9d30c0dd8f0a");
        $response->assertStatus(200);
        $id = $response["id"];

        $this->assertNotEmpty($response["campaign_id"]);
        $this->assertNotEmpty($response["author"]);
        $this->assertNotEmpty($response["created_at"]);
        $this->assertNotEmpty($response["updated_at"]);
        $this->assertCount(4, $response["inputs"]);
        $this->assertEquals($response["inputs"][0]["campaign_id"], $id);
        $this->assertNotEmpty($response["inputs"][0]["type"]);
        $this->assertNotEmpty($response["inputs"][0]["value"]);
    }

    public function test_delete_campaign_by_id()
    {
        $this->json("DELETE", "/api/campaigns/841e8ed8-6bd3-3647-8187-9d30c0dd8f0a")
        ->assertStatus(204);
    }

    public function test_delete_campaign_by_same_id()
    {
        $this->json("DELETE", "/api/campaigns/841e8ed8-6bd3-3647-8187-9d30c0dd8f0a")
            ->assertStatus(404)
        ->assertJson(["error" => "Campaign with this id doesn't exist"]);
    }

    public function test_get_non_existing_campaign_by_id()
    {
        $response = $this->get("/api/campaigns/841e8ed8-6bd3-3647-8187-9d30c0dd8f0a");
        $response->assertStatus(404)
        ->assertJson(["error" => "Campaign '841e8ed8-6bd3-3647-8187-9d30c0dd8f0a' not found"]);
    }
}
