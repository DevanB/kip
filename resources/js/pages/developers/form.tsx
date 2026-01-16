import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Form } from '@inertiajs/react';
import { store, update } from '@/routes/developers';

interface DeveloperFormProps {
    initialData?: {
        id?: number;
        name: string;
        email: string;
        github_username: string | null;
        gitlab_username: string | null;
    };
    isEditing?: boolean;
}

export default function DeveloperForm({ initialData, isEditing = false }: DeveloperFormProps) {
    const formAction = isEditing && initialData?.id 
        ? update(initialData.id).url
        : store().url;

    return (
        <Form
            action={formAction}
            method={isEditing ? 'put' : 'post'}
            className="space-y-6"
        >
            <div>
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    name="name"
                    type="text"
                    defaultValue={initialData?.name || ''}
                    required
                    placeholder="John Doe"
                />
            </div>

            <div>
                <Label htmlFor="email">Email</Label>
                <Input
                    id="email"
                    name="email"
                    type="email"
                    defaultValue={initialData?.email || ''}
                    required
                    placeholder="john@example.com"
                />
            </div>

            <div>
                <Label htmlFor="github_username">GitHub Username</Label>
                <Input
                    id="github_username"
                    name="github_username"
                    type="text"
                    defaultValue={initialData?.github_username || ''}
                    placeholder="johndoe"
                />
            </div>

            <div>
                <Label htmlFor="gitlab_username">GitLab Username</Label>
                <Input
                    id="gitlab_username"
                    name="gitlab_username"
                    type="text"
                    defaultValue={initialData?.gitlab_username || ''}
                    placeholder="john_doe"
                />
            </div>

            <div className="flex gap-2">
                <Button type="submit">
                    {isEditing ? 'Update Developer' : 'Create Developer'}
                </Button>
            </div>
        </Form>
    );
}
