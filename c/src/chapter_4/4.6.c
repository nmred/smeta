#include <stdio.h>
#include <ctype.h>

int main(void)
{
	char answer = 'N';
	double total = 0.0;
	double value = 0.0;
	int count = 0;
	
	printf("\nThis program calculates the average of any number of values.");

	for(;;) {
		printf("\nEnter a value:");
		scanf(" %lf", &value);
		total += value;
		++count;
		
		printf("Do you want to enter another value ? (Y or N):");
		scanf(" %c", &answer);
		if (tolower(answer) == 'n') {
			break;	
		}
	}

	printf("\nThe average is %.2lf\n", total / count);
	return 0;
}
