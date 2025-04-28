<?php

namespace Database\Seeders;

use App\Models\Logo;
use Illuminate\Database\Seeder;

class LogoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default sample logo with inline base64 data
        $sampleLogo = [
            'image_path' => 'storage/logos/sample_logo.png',
            'is_active' => true,
            'base64_image' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAACXBIWXMAAAsTAAALEwEAmpwYAAAF8UlEQVR4nO2daahVRRTHf08tLcvS0jILS8sWl7LFIrLFohLS6EOLBRUFfYmwDxH1QUj7QBEmRRREUgpFtChEmWWLLRQVLdZrmZmVZdmCLV7+8T94eO+ce+fOnDv3npn5/+Aw3Htm7pxZzp41axBCCCGEEEIIIYQQQgghhBBCCCGEEEIIIUS7GQFcDMwFaoFF/lsLzAOuACYAuwDNsJ/FwHhgDPC1/x6uwzlU+fy8FWg6OwOPAmuBzYHXJuBh4BDgEWCj53kUuBdYn+FcGnW+D6whUbqB7cCHQJdnOQ/4wTPXgDuBccAFwAZg5AATLNW5NQ83ZMxkNyX9wLQIh7QH8Kmni32OZnNHxFhEfO4OpqJGXJsB87xnfjwn7JCuiRiLSIddgL/9S/6/IOf8ncAH5pCeCPyc69sZhSOZhHXy3sCHfvdtyHkf9PsN4CugB/g9p81c4KiMYwmFI5nJ7hjg42AiXu5/xwKTPHO8m1HnTL8/1/PsCnzm+V7LaHMdcHhO2UI0jYnAN8BaP0Bt9L812JrdQsYXrNGXA/t63i52f8wBnzJaeH9g3S3EuHF2OJIFwKDc2EIiDAOOsO/XDNwGPANs8PtT/W5L7ANcYYy5XgvOa52fJ9rF1v4Y73cCy+2G+j5RhlggPsbs0RqcCZyQNwOigRxpCpwacKctVx/7fQV5Q0D2VuBPu9DjNd67gUttP7KsVSMQxUU65wC9JphRDMm8AV4EdgCutr5sGWMQcB3wjd97gIsRJRji++TFtoKqeTFaG7DVbMl2BtyE82AKp3qhLwDCx3AhzhjcMLPDlmKFOdHujIsqZPGvOvNcJPqo/4C5wEVm5yyS+Ajwt98va/Zh3o/ATOCXKsVUCcPvtbhOiYhWA+uMmbjKCuZW4D9HdAeEwpFc6fG5B9pVRBpsBX70FdILnGY758uBx7MG7g4f70CrgIVRZyJteoEfLFJ5Pc5z2g2cbusSt1lWgbAeFw29ld1HFW3iJ8/6+B242gJpZ5iDmgx8gZs9Z9hKyTMBr+Ns1Oj8aYjGkbOmBx4J9yIJrMEMfGDCnnw6MDpGD+9h0fPNFkbcG5iDMw/e83NQ3p9YRcK+JAnO9Luj/f7BwfnVgOHAaYGXmBXQOc5izIQ4S5wl58bqmWgYc4CVDco70cP8nxbs42GLSbdVqDXmMvtiBVZLpPsZTLR+JJGofJpbXE2w2VZGg/kZ+K5gHxeZkWRNhVpjziDgJ+AzvysWDYUjmZYpaxzxqKXx+aNCnRHuHvxmkr5W3hWtZUQleqs2UKXWmPPZDjtv8xqr9qw6bQ8QrRwKR/I6rFKr2Jq8Rqs9pE6/KtOaFXaTOaTFlezcqvVWwQ+2k3/fD9xEtfnSPK2PZfRnslUG+w6XGq6XlIYCp+IiuLOs/XJ/2yKdZ87sFGz3mTX5kJSGQp1VknJ/UVVGacK/Cp0Z7lLRc0gWnRnuUtFzSBadGe5SkeGQkp5DsujM1eOQaI3ODHep6DkkiyScGesuhVQsVWCaJ/a5pCDvU+suhVQsVWA87gS26QXeJ9iiZYm9C9zdLUELnlNxwgdS1nGIgqYqsQdwOXBakQdUcbX0k2Q5HGmQcxww1Oo/TLXSQb2ebtDZCcCLwKWN0FpFxuDmZgxOvWABOm7AlbKMZSLwOHA+zkdSN61B87MYp0WL05L1b5jZfdlrOd4ZtG9V2iKt3+PCmNfZfcNKEIZaBUebgjbNaK8qqw+y5XSzb6j6Y/TNwOHFdWOzFeF/D/glpmKiNdSANfbRm1iMyajaRDsVWFbCF9bJtPlgolVgdVllmkWlPidlLPB6BVdMbWJQFW1IFQvQNYr3gbP9fZjdlRMzpJKKvb5VtB/DcTWT84D9cNm+vdjhcJujm/DfcUURQgghhBBCCCGEEEIIIYQQQgghhBBCCPbgf/6wQWOF9cEFAAAAAElFTkSuQmCC' // Sample Aligans "A" logo
        ];
        
        // Check if we already have a logo with this path
        $existingLogo = Logo::where('image_path', $sampleLogo['image_path'])->first();
        
        if (!$existingLogo) {
            Logo::create($sampleLogo);
        } else if (!$existingLogo->base64_image) {
            // Update the existing logo with base64 data
            $existingLogo->update([
                'base64_image' => $sampleLogo['base64_image'],
                'is_active' => true
            ]);
        }
    }
} 