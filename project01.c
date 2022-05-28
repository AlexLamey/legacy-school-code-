#include <stdio.h>
#include <stdlib.h>
#include <signal.h>
#define K 5
#define REQUEST 100
#define PIVOT 200
#define LARGE 300
#define SMALL 400
#define READY 500

int i, fds[K][2], fds1[K][2], id_arr[K];

//the process executed by each child, made distinct by its assigned argument 'id'
int childProcess(int id)
{
    int _id;

    //assign id 
    read(fds[id][0], &_id, sizeof(int));

    // create local variables relevant to reading component.
    FILE *fp;
    int buffer;
    int index = 0;
    int *ptr;

    char filename[100];
    //'dummy variable' to create input_x.txt, which child x will use
    int j = snprintf(filename, 100, "input_%d.txt", _id);
    // printf("%s", filename);
    fp = fopen(filename, "r");

    //no .txt file with the assigned name.
    if (fp == NULL)
    {
        printf("%s does not exists \n", filename);
        exit(1);
    }
    else
    {
        // failsafe if memory cannot be set aside for soon to be written data
        if ((ptr = (int *)malloc(sizeof(int))) == NULL)
        {
            printf("failure!\n");
            exit(1);
        }
        else
        {
            while (fscanf(fp, "%d", &buffer) == 1)
            {   //failsafe if there is not enough data to write newfound data onto.
                if ((ptr = realloc(ptr, sizeof(int) * (index + 1))) == NULL)
                {
                    printf("failure!\n");
                    free(ptr);
                    exit(1);
                }
                ptr[index] = buffer;
                index++;
            }
            //failsafe if end of file is unexpectedly reached
            if (fclose(fp) == EOF)
            {
                printf("failure!\n");
                exit(1);
            }
        }
    }

    // write(READY)
    write(fds1[id][1], &index, sizeof(int));

    int pivot;

    while (1)
    {
        int val = 0;
        int new_index = 0;
        int new_index1 = 0;
        int signal = 0;

        read(fds[id][0], &signal, sizeof(int));

        //handler for whatever signal is set to:
        //REQUEST: choose a random value among the childs ptr. The code will see if this value is the median
        //PIVOT: determine how many numbers handled by child exceed the desired value
        //SMALL: dynamically recreate the array such that all values are smaller than the desired value
        //LARGE: dynamically recreate the array such that all values are larger than the desired value 
        //default: invalid signal recieved
        switch (signal)
        {
        case REQUEST:
            printf("child %d requested to send \n", id + 1);
            // int val = 0;
            if (index == 0)
            {
                val = -1;
            }
            else
            {
                val = ptr[(rand() % index)];
                printf("writing %d \n", val);
            }
            write(fds1[id][1], &val, sizeof(int));
            break;

        case PIVOT:
            read(fds[id][0], &pivot, sizeof(int));
            printf("child %d pivot = %d\n", id + 1, pivot);
            int count = 0;
            for (int i = 0; i < index; i++)
            {
                if (ptr[i] > pivot)
                {
                    count++;
                }
            }
            printf("child %d sent %d\n", id + 1, count);
            write(fds1[id][1], &count, sizeof(int));
            break;

        case LARGE:
            // int new_index = 0;
            for (int i = 0; i < index; i++)
            {
                if (ptr[i] <= pivot)
                {
                    ptr[new_index] = ptr[i];
                    new_index++;
                }
            }
            index = new_index;
            break;

        case SMALL:
            // int new_index1 = 0;
            for (int i = 0; i < index; i++)
            {
                if (ptr[i] >= pivot)
                {
                    ptr[new_index1] = ptr[i];
                    new_index1++;
                }
            }
            index = new_index1;
            break;
        default:
            // no signal recieved
            printf("nothing\n");
            break;
        }
    }
    exit(1);
}

int parentProcess()
{
    int m = 0;
    int buf;
    for (int i = 0; i < K; i++)
    {
        // assigns id to child
        write(fds[i][1], &i, sizeof(int));
    }

    for (int i = 0; i < K; i++)
    {
        read(fds1[i][0], &buf, sizeof(int)); // waiting for child to send READY
        printf("signal READY recieved by child %d\n", i + 1);
        m += buf;
    }

    printf("Parent ready!\n");
    //set local variable k (different from defined K), and isEven as a flag to account for if the median is among 
    //a sum of even or odd numbers
    int k = m / 2;
    int isEven = 0;
    if (k % 2 == 0)
        isEven = 1;
    printf("initial k = %d \n", k);


    while (1)
    {
        //randomly determine which id will recieve the request via requested, 
        int requested = (rand() % K);
        int signal = REQUEST;
        int pivot;
        int buffer;

        //tell child it is to send a signal back to the parent
        write(fds[requested][1], &signal, sizeof(int));

        // wait for response from random child
        //printf("awaiting response...\n");
        read(fds1[requested][0], &pivot, sizeof(int));

        //failsafe for empty array being read from. breaks out of loop to avoid meaningless pivot attempt.
        if (pivot < 0)
        {
            continue;
        }
        printf("received pivot %d", pivot);

        // send pivot to all children
        signal = PIVOT;
        m = 0;
        for (int i = 0; i < K; i++)
        {
            write(fds[i][1], &signal, sizeof(int));
            write(fds[i][1], &pivot, sizeof(int));
            read(fds1[i][0], &buffer, sizeof(int));
            m += buffer;
        }

        printf("\n k = %d and m = %d \n", k, m);
        // sleep(3);

        //chosen value is smaller than median, SMALL signal is sent
        if (m > k)
        {
            printf("Too small. Trying again\n");
            signal = SMALL;
            for (int i = 0; i < K; i++)
            {
                write(fds[i][1], &signal, sizeof(int));
            }
        }
        //chosen value is larger than median, LARGE signal is sent 
        else if (m < k)
        {
            printf("Too large. Trying again\n");
            signal = LARGE;
            for (int i = 0; i < K; i++)
            {
                write(fds[i][1], &signal, sizeof(int));
            }

            k -= m;
        }
        else if (m == k)
        {
            if (isEven != 0)
            {
                printf("%d is the median\n", pivot);
                printf("median found!\n");
            }
            //TODO: find k + 1'th element and determine the average between them
            else
            {
            printf("%d is the median\n", pivot);
            }

            // terminate children before allowing parent process to end
            
            for (int i = 0; i < K; i++)
            {
                
                kill(id_arr[i], SIGINT);
                
                waitpid(-1, NULL, 0);
                printf("Child %d terminated!\n", i + 1);
            }
            exit(0);
        }
    }
}

int main(int argc, char const *argv[])
{

    int pid;

    pipe(fds[0]);  // pipe used by the child 1 process
    pipe(fds[1]);  // pipe used by the child 2 process
    pipe(fds[2]);  // pipe used by the child 3 process
    pipe(fds[3]);  // pipe used by the child 4 process
    pipe(fds[4]);  // pipe used by the child 5 process
    pipe(fds1[0]); // pipe used by the child 1 process
    pipe(fds1[1]); // pipe used by the child 2 process
    pipe(fds1[2]); // pipe used by the child 3 process
    pipe(fds1[3]); // pipe used by the child 4 process
    pipe(fds1[4]); // pipe used by the child 5 process
    if ((pid = fork()) == 0)
    {
        
        childProcess(0);
        
    }
    else
    {
        // parent
        id_arr[0] = pid;
        if ((pid = fork()) == 0)
        {
            //child
            childProcess(1);
        }
        else
        {
            // parent
            id_arr[1] = pid;
            if ((pid = fork()) == 0)
            {
                //child
                childProcess(2);
            }
            else
            {
                // parent
                id_arr[2] = pid;
                if ((pid = fork()) == 0)
                {
                    //child
                    childProcess(3);
                }
                else
                {
                    // parent
                    id_arr[3] = pid;
                    if ((pid = fork()) == 0)
                    {
                        // child
                        childProcess(4);
                    }
                    else
                    {
                        // parent
                        id_arr[4] = pid;
                        
                        parentProcess();
                        
                    }
                }
            }
        }
    }

    return 0;
}
