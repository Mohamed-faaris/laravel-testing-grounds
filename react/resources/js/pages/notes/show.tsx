import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import { destroy, edit, index } from '@/routes/notes';
import type { BreadcrumbItem } from '@/types';

interface Note {
    id: number;
    title: string;
    content: string;
    created_at: string;
    updated_at: string;
    user?: {
        name: string;
        email: string;
    };
}

interface Props {
    note: Note;
    auth: {
        user: {
            role: string;
        };
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notes',
        href: index().url,
    },
    {
        title: 'View',
        href: '#',
    },
];

export default function ShowNote({ note, auth }: Props) {
    const handleDelete = () => {
        if (confirm('Are you sure you want to delete this note?')) {
            router.delete(destroy({ note: note.id }).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={note.title} />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <Card className="mx-auto w-full max-w-3xl">
                    <CardHeader>
                        <div className="flex items-start justify-between">
                            <div>
                                <CardTitle className="text-2xl">
                                    {note.title}
                                </CardTitle>
                                <CardDescription className="mt-2 flex items-center gap-2">
                                    {auth.user.role === 'admin' &&
                                        note.user && (
                                            <Badge variant="secondary">
                                                By {note.user.name}
                                            </Badge>
                                        )}
                                    <span>
                                        Created:{' '}
                                        {new Date(
                                            note.created_at,
                                        ).toLocaleDateString()}
                                    </span>
                                    <span>•</span>
                                    <span>
                                        Updated:{' '}
                                        {new Date(
                                            note.updated_at,
                                        ).toLocaleDateString()}
                                    </span>
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="prose dark:prose-invert max-w-none">
                            <p className="whitespace-pre-wrap">
                                {note.content}
                            </p>
                        </div>
                    </CardContent>
                    <CardContent className="flex gap-4 pt-0">
                        <Button variant="outline" asChild>
                            <Link href={edit({ note: note.id }).url}>Edit</Link>
                        </Button>
                        <Button variant="destructive" onClick={handleDelete}>
                            Delete
                        </Button>
                        <Button variant="outline" asChild className="ml-auto">
                            <Link href={index().url}>Back to Notes</Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
