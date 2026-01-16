import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/developers';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import DeveloperForm from './form';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Developers',
        href: index().url,
    },
    {
        title: 'Create',
        href: '#',
    },
];

export default function Create() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Developer" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div>
                    <h1 className="text-2xl font-bold">Add New Developer</h1>
                    <p className="text-muted-foreground">Create a new developer record in the directory.</p>
                </div>
                <Card className="max-w-2xl">
                    <CardHeader>
                        <CardTitle>Developer Details</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <DeveloperForm />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
