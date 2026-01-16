import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/developers';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import DeveloperForm from './form';

interface DeveloperData {
    id: number;
    name: string;
    email: string;
    github_username: string | null;
    gitlab_username: string | null;
}

interface EditProps {
    developer: DeveloperData;
}

export default function Edit({ developer }: EditProps) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Developers',
            href: index().url,
        },
        {
            title: developer.name,
            href: '#',
        },
        {
            title: 'Edit',
            href: '#',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${developer.name}`} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-bold">Edit Developer</h1>
                    <p className="text-muted-foreground">Update developer information.</p>
                </div>
                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Developer Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <DeveloperForm 
                            initialData={developer}
                            isEditing={true}
                        />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
