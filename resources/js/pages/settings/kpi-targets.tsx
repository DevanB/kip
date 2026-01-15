import { update } from '@/actions/App/Http/Controllers/Settings/KpiTargetController';
import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit } from '@/routes/kpi-targets';
import { type BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { Form, Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'KPI Target settings',
        href: edit().url,
    },
];

interface KpiTargetsProps {
    rtoTarget: number;
    rpoTarget: number;
}

export default function KpiTargets({ rtoTarget, rpoTarget }: KpiTargetsProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="KPI Target settings" />

            <h1 className="sr-only">KPI Target Settings</h1>

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="KPI Targets" description="Configure your target thresholds for RTO and RPO metrics" />

                    <Form
                        {...update.form()}
                        options={{
                            preserveScroll: true,
                        }}
                        className="space-y-6"
                    >
                        {({ processing, recentlySuccessful, errors }) => (
                            <>
                                <div className="grid gap-2">
                                    <Label htmlFor="rto_target">RTO Target (minutes)</Label>
                                    <Input
                                        id="rto_target"
                                        type="number"
                                        name="rto_target"
                                        min="1"
                                        defaultValue={rtoTarget}
                                        required
                                        placeholder="e.g., 60"
                                    />
                                    <p className="text-muted-foreground text-sm">
                                        Recovery Time Objective - the maximum acceptable time to restore services
                                    </p>
                                    <InputError className="mt-2" message={errors.rto_target} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="rpo_target">RPO Target (minutes)</Label>
                                    <Input
                                        id="rpo_target"
                                        type="number"
                                        name="rpo_target"
                                        min="1"
                                        defaultValue={rpoTarget}
                                        required
                                        placeholder="e.g., 60"
                                    />
                                    <p className="text-muted-foreground text-sm">
                                        Recovery Point Objective - the maximum acceptable data loss in time
                                    </p>
                                    <InputError className="mt-2" message={errors.rpo_target} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <Button disabled={processing}>{processing ? 'Saving...' : 'Save'}</Button>

                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-neutral-600">Saved</p>
                                    </Transition>
                                </div>
                            </>
                        )}
                    </Form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
